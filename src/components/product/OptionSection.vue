<template>
  <div class="option-section">
    <div v-for="(option, index) in options" :key="index" class="q-mb-md option">
      <div class="flex items-center">
        <base-label>{{ $t("label.option") }} {{ index + 1 }}</base-label>
        <q-space />
        <base-btn
          v-if="options.length > 1"
          link
          class="q-ml-sm"
          padding="0"
          :label="$t('label.remove')"
          @click="onRemoveOption(index, option)"
        />
      </div>
      <div class="row q-col-gutter-sm">
        <div class="col-sm-2 col-xs-12">
          <q-select
            options-dense
            dense
            outlined
            v-model="option.name"
            use-input
            hide-selected
            fill-input
            input-debounce="0"
            :options="attributesList"
            @input-value="(val) => (option.name = val)"
            hide-dropdown-icon
          />
        </div>
        <div class="col-sm-10 col-xs-12">
          <q-select
            options-dense
            dense
            outlined
            v-model="option.values"
            map-options
            use-chips
            use-input
            multiple
            hide-dropdown-icon
            input-debounce="0"
            option-label="name"
            option-value="id"
            @new-value="(val, done) => createValue(val, done, option)"
            @update:model-value="(val) => onChange(val, option)"
            @add="({ value }) => onAddOptionValue(value, option, index)"
            @remove="({ value }) => onRemoveOptionValue(value, option, index)"
            @filter="
              (val, update, abort) =>
                onLoadAttributeOptions(val, update, abort, option)
            "
            :options="values"
            hint="Separate options with comma"
          />
        </div>
      </div>
      <q-separator class="q-mt-sm" />
    </div>
    <div class="q-gutter-sm">
      <q-btn
        no-caps
        outline
        :label="$t('label.selectOptionsFromAttribue')"
        color="primary"
        :loading="loading"
      >
        <q-menu fit @before-show="onLoadAttributes">
          <q-list
            dense
            v-if="!loading"
            style="min-width: 100px"
            class="q-pa-sm"
          >
            <q-item
              class="rounded-borders"
              @click="
                onAddOption({
                  id: false,
                  name: item.name,
                  is_custom: false,
                  is_variant: true,
                  attribute_id: item.id,
                  values: [],
                })
              "
              v-for="item in attributes.data"
              :disable="
                options.map((option) => option.attribute_id).includes(item.id)
              "
              :key="item.id"
              clickable
              v-ripple
              v-close-popup
            >
              <q-item-section>{{ item.name }}</q-item-section>
            </q-item>
            <q-item v-if="attributes.meta && attributes.meta.last_page > 1">
              <q-item-section class="flex-center">
                <q-pagination
                  v-model="attributes.meta.current_page"
                  :max="attributes.meta.last_page"
                  input
                  @input="
                    (val) => {
                      getAttributes({
                        page: val,
                      });
                    }
                  "
                />
              </q-item-section>
            </q-item>
          </q-list>
        </q-menu>
      </q-btn>
      <q-btn
        no-caps
        outline
        :label="$t('label.createCustomOption')"
        @click="
          onAddOption({
            id: false,
            name: attributesList[
              Math.floor(Math.random() * attributesList.length)
            ],
            is_custom: true,
            attribute_id: false,
            values: [],
          })
        "
      />
    </div>
  </div>
</template>

<script>
import { cloneDeep } from "lodash";
import { mapActions, mapState } from "pinia";
import { useAttributeStore } from "src/stores/product/attribute";
import { useVariantStore } from "src/stores/product/variant";

const attributesList = ["Color", "Size", "Design", "Weight", "Material"];

export default {
  props: {
    productId: {
      required: false,
      type: Number,
    },
    modelValue: {
      required: true,
      type: Array,
    },
    creating: {
      required: false,
      type: Boolean,
      default: false,
    },
  },
  emits: ["update:model-value", "update:variants"],
  data() {
    return {
      attributesList,
      attribute: null,
      attributes: {},
      values: [],
      options: this.modelValue,
      loading: false,
    };
  },
  methods: {
    ...mapActions(useVariantStore, ["removeOption", "removeOptionValue"]),
    ...mapActions(useAttributeStore, {
      attributeList: "get",
      attributeShow: "show",
    }),
    createValue(val, done, option) {
      console.func(
        "components/product/OptionSection:method.createValue()",
        arguments
      );
      const value = option.values.find((item) => item.name === val);
      if (!value) {
        done({ name: val }, "add-unique");
      } else {
        done(value, "add-unique");
      }
    },
    onChange(values, option) {
      console.func(
        "components/product/OptionSection:method.onChange()",
        arguments
      );
      this.$emit("update:model-value", this.options);
    },
    onAddOption(option) {
      console.func(
        "components/product/OptionSection:method.onAddOption()",
        arguments
      );
      this.options.push(option);
      this.$emit("update:model-value", this.options);
    },
    onRemoveOption(index, option) {
      console.func(
        "components/product/OptionSection:method.onRemoveOption()",
        arguments
      );
      this.$core.confirm(`Are you sure you want to remove?`).then(() => {
        this.removeOption({
          position: index,
          option: option,
        })
          .then(() => {
            this.options.splice(index, 1);
            this.$emit("update:variants", this.options);
          })
          .catch((error) => {
            this.$core.error(error, { title: "Error" });
          });
      });
    },
    onAddOptionValue(value, option, index) {
      console.func(
        "components/product/OptionSection:method.onAddOptionValue()",
        arguments
      );
      const options = cloneDeep(this.options);
      options[index].values.push(value);
      this.$emit("update:variants", options);
    },
    onRemoveOptionValue(value, option, index) {
      console.func(
        "components/product/OptionSection:method.onRemoveOptionValue()",
        arguments
      );
      if (!this.hasVariant(index, value.name)) return false;
      this.removeOptionValue({
        position: index,
        value: value.name,
        option: option,
      }).catch((error) => {
        this.$core.error(error, { title: "Error" });
      });
    },
    getAttributes(props) {
      console.func(
        "components/product/OptionSection:method.getAttributes()",
        arguments
      );
      this.loading = true;
      this.attributeList(props)
        .then((response) => {
          this.attributes = response;
          this.loading = false;
        })
        .catch((error) => {
          this.loading = false;
          this.$core.error(error, { title: "Error" });
        });
    },
    onLoadAttributes(evt) {
      console.func(
        "components/product/OptionSection:method.onLoadAttributes()",
        arguments
      );
      this.getAttributes({});
    },
    onLoadAttributeOptions(val, update, abort, option) {
      console.func(
        "components/product/OptionSection:methods.onLoadAttributeOptions()",
        arguments
      );
      this.values = [];
      if (!option.attribute_id) {
        abort();
        return;
      }
      this.attributeShow(option.attribute_id).then((attribute) => {
        update(() => {
          const needle = val.toLowerCase();
          this.values = attribute.values.filter(
            (item) => item.name.toLowerCase().indexOf(needle) > -1
          );
        });
      });
    },
  },
  computed: {
    ...mapState(useVariantStore, ["hasVariant"]),
  },
  watch: {
    modelValue: {
      deep: true,
      handler(val) {
        this.options = val;
      },
    },
  },
};
</script>
