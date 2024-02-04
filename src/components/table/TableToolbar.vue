<template>
  <div
    :class="{
      'top-right-inner': true,
      'q-gutter-sm': !responsive,
      'responsive q-gutter-x-sm': responsive,
    }"
  >
    <template v-if="responsive">
      <div v-if="hasToolbarItems" class="toolbar-item">
        <base-btn
          @click="$refs.dialog.show()"
          color="grey-4"
          class="text-black"
          icon="fad fa-filter-list"
        >
          <q-dialog persistent ref="dialog" position="bottom">
            <q-card class="full-width" square>
              <q-toolbar class="bg-grey-3">
                <q-toolbar-title class="text-subtitle2">
                  Filters
                </q-toolbar-title>
                <q-btn
                  v-close-popup
                  flat
                  round
                  dense
                  size="sm"
                  icon="fal fa-times"
                />
              </q-toolbar>
              <q-card-section style="max-height: 50vh" class="scroll">
                <div class="dialog-items q-gutter-y-md">
                  <div
                    v-for="(item, index) in toolbarItems"
                    :key="index"
                    v-show="hasPermission(item)"
                    class="toolbar-item"
                    :width="item.width"
                  >
                    <template v-if="item.component">
                      <component
                        :is="item.component"
                        :model-value="pagination[item.key]"
                        @update:model-value="onAction(item, $event)"
                        v-bind="item"
                        :label="getToolbarLabel(item)"
                      />
                    </template>
                    <template v-else>
                      <q-btn
                        @click="onAction(item)"
                        v-bind="item"
                        :label="getToolbarLabel(item)"
                        no-caps
                        v-close-popup
                      />
                    </template>
                  </div>
                </div>
              </q-card-section>
            </q-card>
          </q-dialog>
        </base-btn>
      </div>
    </template>
    <template v-else>
      <div v-if="canDelete" class="toolbar-item">
        <q-btn
          :disable="
            !hasPermission({
              permission: 'Delete',
            })
          "
          icon="fas fa-trash-alt"
          color="negative"
          flat
          @click="
            onAction({
              action: 'delete',
            })
          "
          class="toolbar"
          :label="$t('delete')"
          no-caps
        />
      </div>
      <div v-else-if="canRestore" class="toolbar-item">
        <q-btn
          :disable="
            !hasPermission({
              permission: 'Delete',
            })
          "
          icon="fas fa-trash-undo"
          color="primary"
          flat
          @click="
            onAction({
              action: 'restore',
            })
          "
          class="toolbar"
          :label="$t('restore')"
          no-caps
        />
      </div>
      <div
        v-for="(item, index) in toolbarItems"
        :key="index"
        v-show="hasPermission(item)"
        class="toolbar-item"
        :width="item.width"
      >
        <template v-if="item.component">
          <component
            :is="item.component"
            :model-value="pagination[item.key]"
            @update:model-value="onAction(item, $event)"
            v-bind="item"
            :label="getToolbarLabel(item)"
          >
            <base-tooltip v-if="item.tooltip && !responsive" position="top">
              {{ item.tooltip }}
            </base-tooltip>
          </component>
        </template>
        <template v-else>
          <q-btn
            @click="onAction(item)"
            v-bind="item"
            :label="getToolbarLabel(item)"
            no-caps
          >
            <base-tooltip v-if="item.tooltip && !responsive" position="top">
              {{ item.tooltip }}
            </base-tooltip>
          </q-btn>
        </template>
      </div>
    </template>
    <slot name="top-right-after"></slot>
  </div>
</template>

<script>
export default {
  props: {
    onAction: Function,
    hasPermission: Function,
    canDelete: Boolean,
    canRestore: Boolean,
    responsive: Boolean,
    toolbarItems: Array,
    pagination: Object,
  },
  methods: {
    getToolbarLabel(item) {
      return this.responsive && item.tooltip ? item.tooltip : item.label;
    },
  },
  computed: {
    hasToolbarItems() {
      return this.toolbarItems.length > 0;
    },
  },
};
</script>
