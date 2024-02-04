<template>
  <div class="base-table">
    <q-drawer
      v-if="hasFilter"
      side="right"
      ref="filter"
      bordered
      :width="280"
      :breakpoint="500"
      class="base-table-filter"
    >
      <div class="absolute-top bg-grey-3 filter-title">
        <q-toolbar light>
          <q-toolbar-title class="q-ml-xs text-subtitle2">
            {{ filterTitle }}
          </q-toolbar-title>
          <q-btn
            icon="close"
            size="sm"
            flat
            round
            dense
            @click="$refs.filter.hide()"
          />
        </q-toolbar>
        <q-separator />
      </div>
      <q-card class="bg-transparent" flat square>
        <q-card-section class="row q-col-gutter-md">
          <slot name="advanced-filter" v-bind:syncPagination="syncPagination">
            <div
              v-for="(item, index) in filters"
              :key="index"
              class="col-12 filter-item"
            >
              <base-label v-if="item.title">{{ item.title }}</base-label>
              <component
                :is="item.component"
                v-model="syncPagination[item.key]"
                @update:model-value="sendServerRequest"
                v-bind="item"
              />
            </div>
          </slot>
        </q-card-section>
      </q-card>
    </q-drawer>
    <template v-if="syncPagination.loaded || loaded">
      <q-table
        :class="{
          'main-table': true,
          'last-column-sticky': hasActionItems,
        }"
        square
        :rows="rows"
        :columns="columns"
        :bordered="bordered"
        :grid="grid || responsive"
        :hide-header="hideHeader || responsive"
        :flat="flat"
        row-key="id"
        :hide-pagination="hidePagination"
        :rows-per-page-options="rowsPerPageOptions"
        :filter="syncPagination.filter"
        v-model:pagination="syncPagination"
        :loading="loading"
        :selection="selection"
        :selected="selected"
        @update:selected="onUpdateSelected"
        @request="onRequest"
        @row-click="onRowClicked"
        :no-data-label="noDataLabel"
        :visible-columns="visibleColumns"
      >
        <template v-if="!hideTop" v-slot:top="scope">
          <slot
            name="top"
            v-bind="scope"
            v-bind:permissions="permissions"
            v-bind:selected="selected"
            v-bind:toolbar="toolbarItems"
            v-bind:onToolbarClicked="onToolbarClicked"
          >
            <div class="col">
              <div class="row q-col-gutter-md">
                <div :class="`top-left col-sm-${topLeftWidth} col-xs-9`">
                  <slot
                    name="top-left"
                    v-bind="scope"
                    v-bind:permissions="permissions"
                    v-bind:selected="selected"
                    v-bind:toolbar="toolbarItems"
                  >
                    <q-input
                      v-model="syncPagination.filter"
                      outlined
                      clearable
                      :placeholder="$t(quickSearchPlaceholder)"
                      debounce="1000"
                      dense
                    >
                      <template v-slot:append>
                        <q-icon
                          v-if="!syncPagination.filter"
                          name="fas fa-search"
                          size="16px"
                        />
                      </template>
                    </q-input>
                  </slot>
                </div>
                <div
                  :class="`top-right col-sm-${topRightWidth} col-xs-3 text-right`"
                  @click.stop
                >
                  <slot
                    name="top-right"
                    v-bind="scope"
                    v-bind:permissions="permissions"
                    v-bind:selected="selected"
                    v-bind:toolbar="toolbarItems"
                  >
                    <table-toolbar
                      :can-delete="canDelete"
                      :can-restore="canRestore"
                      :toolbar-items="toolbarItems"
                      :pagination="syncPagination"
                      :responsive="responsive"
                      :has-permission="hasPermission"
                      @action="onToolbarClicked"
                    >
                      <template #top-right-after>
                        <slot name="top-right-after"></slot>
                      </template>
                    </table-toolbar>
                  </slot>
                </div>
              </div>
              <slot name="top-bottom"></slot>
            </div>
          </slot>
        </template>

        <template v-slot:header-selection="scope">
          <slot name="header-selection" v-bind="scope">
            <q-checkbox dense size="sm" v-model="scope.selected" />
          </slot>
        </template>

        <template v-slot:body-selection="scope">
          <slot name="body-selection" v-bind="scope">
            <q-checkbox dense size="sm" v-model="scope.selected" />
          </slot>
        </template>

        <template v-if="!!$slots.body" v-slot:body="props">
          <slot name="body" v-bind="props"></slot>
        </template>

        <template
          v-for="slot in tableHeaderSlots"
          v-slot:[slot]="props"
          :key="slot"
        >
          <q-th :props="props">
            <slot
              :name="slot"
              v-bind="props"
              v-bind:permissions="permissions"
              v-bind:actions="actionItems"
              v-bind:onActionClicked="onActionClicked"
            >
              <span v-html="props.value"></span>
            </slot>
          </q-th>
        </template>

        <template
          v-for="slot in tableBodySlots"
          v-slot:[slot]="props"
          :key="slot"
        >
          <q-td :props="props">
            <slot
              :name="slot"
              v-bind="props"
              v-bind:permissions="permissions"
              v-bind:actions="actionItems"
              v-bind:onActionClicked="onActionClicked"
            >
              <span v-html="props.value"></span>
            </slot>
          </q-td>
        </template>

        <template
          v-for="slot in tableOtherSlots"
          v-slot:[slot]="props"
          :key="slot"
        >
          <slot
            :name="slot"
            v-bind="props"
            v-bind:permissions="permissions"
            v-bind:actions="actionItems"
            v-bind:onActionClicked="onActionClicked"
          ></slot>
        </template>

        <template v-slot:body-cell-actions="props">
          <q-td :props="props">
            <slot name="actions" v-bind="props">
              <q-btn
                round
                flat
                dense
                icon="fal fa-ellipsis-h"
                @click.stop
                v-show="!props.row.disable && hasActionItems"
                :loading="props.key === innerLoading"
              >
                <q-menu dense anchor="bottom end" self="top end">
                  <q-list
                    class="q-pa-sm actions"
                    dense
                    style="min-width: 100px"
                  >
                    <base-item
                      v-for="(item, index) in actionItems"
                      :key="index"
                      v-show="hasPermission(item, props)"
                      @click="onActionClicked(item, props)"
                      :icon="getValue(item, props, 'icon')"
                      :label="getValue(item, props, 'label')"
                    />
                  </q-list>
                </q-menu>
              </q-btn>
            </slot>
          </q-td>
        </template>

        <template v-slot:item="props">
          <slot
            name="item"
            v-bind="props"
            v-bind:permissions="permissions"
            v-bind:actions="actionItems"
            v-bind:onActionClicked="onActionClicked"
            v-bind:hasPermission="hasPermission"
          >
            <div v-bind:class="cardClass" class="col-xs-12 col-sm-12">
              <q-item
                :class="{
                  'body-item': true,
                  'bg-green-2': props.selected,
                  'bg-white': !props.selected,
                }"
                @click.stop="(evt) => $emit('row-clicked', evt, props.row)"
                clickable
              >
                <q-item-section
                  v-if="['single', 'multiple'].includes(selection)"
                  avatar
                  class="body-selection"
                >
                  <slot name="body-selection" v-bind="props">
                    <q-checkbox dense size="sm" v-model="props.selected" />
                  </slot>
                </q-item-section>
                <slot
                  name="item-section"
                  v-bind:props="props"
                  v-bind:permissions="permissions"
                  v-bind:actions="actionItems"
                  v-bind:onActionClicked="onActionClicked"
                  v-bind:hasPermission="hasPermission"
                >
                  <q-item-section>
                    <slot name="section-body" v-bind="props">
                      <q-item-label
                        class="q-mb-xs"
                        v-for="(col, index) in props.cols.filter(
                          (item) =>
                            !['actions'].includes(item.name) &&
                            canVisiable(item.name)
                        )"
                        :tabindex="index"
                        :key="col.name"
                      >
                        <div
                          v-if="!hideItemLabel"
                          class="q-table__grid-item-title"
                        >
                          {{ col.label }}
                        </div>
                        <slot
                          :name="`body-cell-${col.name}`"
                          v-bind="props"
                          v-bind:col="col"
                          v-bind:value="col.value"
                        >
                          <span v-html="col.value"></span>
                        </slot>
                      </q-item-label>
                    </slot>
                  </q-item-section>
                </slot>
                <q-item-section
                  v-if="canVisiable('actions') && hasActionItems"
                  side
                >
                  <slot name="actions" v-bind="props">
                    <q-btn
                      round
                      flat
                      dense
                      icon="fal fa-ellipsis-h"
                      @click.stop
                      v-show="!props.row.disable"
                      :loading="props.key === innerLoading"
                    >
                      <q-menu dense anchor="bottom end" self="top end">
                        <q-list
                          class="q-pa-sm actions"
                          dense
                          style="min-width: 100px"
                        >
                          <base-item
                            v-for="(item, index) in actionItems"
                            :key="index"
                            v-show="hasPermission(item, props)"
                            @click="onActionClicked(item, props)"
                            :icon="getValue(item, props, 'icon')"
                            :label="getValue(item, props, 'label')"
                          />
                        </q-list>
                      </q-menu>
                    </q-btn>
                  </slot>
                </q-item-section>
              </q-item>
              <q-separator />
            </div>
          </slot>
        </template>

        <template v-slot:bottom-row="cols">
          <slot v-bind="cols" name="bottom-row"></slot>
        </template>

        <template v-if="!!$slots.bottom" v-slot:bottom="scope">
          <slot name="bottom" v-bind="scope"></slot>
        </template>
        <template v-slot:no-data="props">
          <div class="full-width row">
            <div class="col">
              <slot v-bind="props" name="no-data">
                <base-no-records
                  icon="fad fa-triangle-exclamation"
                  :message="props.message"
                  height="300px"
                  icon-size="75px"
                />
              </slot>
            </div>
          </div>
        </template>
      </q-table>
    </template>
    <template v-else>
      <slot name="pre-loader">
        <table-skeleton :hide-top="hideTop" :row-numners="8" />
      </slot>
    </template>
  </div>
</template>

<script>
import { map, cloneDeep } from "lodash";
import TableSkeleton from "../skeleton/TableSkeleton.vue";
import TableToolbar from "../table/TableToolbar.vue";

const getValue = (item, props, key) =>
  typeof item[key] === "function" ? item[key](props.row) : item[key];

const exportMethod = (item, store) => {
  if (typeof item.method === "function") {
    return item.method;
  } else if (store) {
    return store.get;
  } else {
    return false;
  }
};

const exceptSlots = [
  "advanced-filter",
  "pre-loader",
  "header-selection",
  "body-selection",
  "body-cell-actions",
  "body",
  "bottom",
  "bottom-row",
  "item",
  "item-selection",
  "top",
  "top-left",
  "top-right",
  "top-bottom",
];

export default {
  components: { TableSkeleton, TableToolbar },
  data() {
    return {
      syncPagination: cloneDeep(this.pagination),
      getValue,
      innerLoading: undefined,
    };
  },
  props: {
    store: {
      required: false,
    },
    module: {
      required: false,
      type: Object,
    },
    columns: {
      required: true,
      type: Array,
      default: () => [],
    },
    rows: {
      required: true,
      type: Array,
      default: () => [],
    },
    onRequest: {
      type: Function,
      default: () => void 0,
    },
    onRowClicked: Function,
    toolbar: {
      required: false,
      type: Array,
      default: () => [],
    },
    actions: {
      required: false,
      type: Array,
      default: () => [],
    },
    filters: {
      required: false,
      type: Array,
      default: () => [],
    },
    pagination: {
      required: false,
      type: Object,
      default: () => ({
        loaded: false,
      }),
    },
    grid: Boolean,
    hidePagination: Boolean,
    hideItemLabel: Boolean,
    noActions: Boolean,
    noTrash: Boolean,
    rowsPerPageOptions: {
      required: false,
      type: Array,
      default: () => [15, 30, 50],
    },
    filter: {
      required: false,
      type: Boolean,
      default: () => true,
    },
    bordered: Boolean,
    flat: {
      required: false,
      type: Boolean,
      default: () => true,
    },
    hideHeader: Boolean,
    hideTop: Boolean,
    noPermissions: Boolean,
    loading: Boolean,
    loaded: Boolean,
    preventDefault: Boolean,
    selection: {
      required: false,
      description: "Available options: single, multiple and none",
      type: String,
      default: "none",
    },
    selected: {
      type: Array,
      default: () => [],
    },
    quickSearchPlaceholder: {
      required: false,
      type: String,
      default: "placeholder.quickSearch",
    },
    filterTitle: {
      required: false,
      type: String,
      default: "Filters",
    },
    filterIcon: {
      required: false,
      type: String,
      default: "fal fa-filter",
    },
    relatedDeleteMessage: {
      required: false,
      type: [String, Boolean],
      default:
        "Please note that once you permanently delete :type :module, any related(s) where they have added to will lose them too.",
    },
    noDataLabel: {
      type: String,
      default: undefined,
    },
    cardClass: {
      type: String,
      default: undefined,
    },
    topRightWidth: {
      required: false,
      type: Number,
      default: 9,
      description: "max value 12 and min value 1",
      validator(value) {
        return value <= 12 || value >= 1;
      },
    },
    visibleColumns: Array,
    hasActions: Boolean,
    tableKey: String,
    noResponsive: Boolean,
  },
  watch: {
    pagination: {
      deep: true,
      handler(val) {
        this.syncPagination = cloneDeep(val);
        if (this.tableKey) {
          this.$core.setPagination(this.tableKey, cloneDeep(val));
        }
      },
    },
  },
  methods: {
    onUpdateSelected(value) {
      console.func(
        "components/base/BaseTable:methods.onUpdateSelected()",
        arguments
      );
      this.$emit("update:selected", value);
    },
    onUpdatePagination(value) {
      console.func(
        "components/base/BaseTable:methods.onUpdatePagination()",
        arguments
      );
      this.$emit("update:pagination", value);
    },
    async onActionClicked(item, props) {
      console.func(
        "components/base/BaseTable:methods.onActionClicked()",
        arguments
      );
      this.$emit("action-clicked", item.action, props.row);

      // checked default actions status
      if (this.preventDefault) return false;

      this.innerLoading = props.key;

      switch (item.action) {
        case "delete":
          this.hasStore();
          await this.$core
            .confirm(
              this.$t("dialog.info.delete", {
                module: this.$t(this.module.singular),
              })
            )
            .then(() => {
              this.store
                .delete(props.row.id)
                .then(() => {
                  this.sendServerRequest();
                })
                .catch((error) => {
                  this.$core.error(error, {
                    title: this.$t("dialog.title.error"),
                  });
                });
            })
            .catch(() => {
              //
            });
          break;
        case "restore":
          this.hasStore();
          await this.$core
            .confirm(
              this.$t("dialog.info.restore", {
                module: this.$t(this.module.singular),
              })
            )
            .then(() => {
              this.store
                .restore(props.row.id)
                .then(() => {
                  this.sendServerRequest();
                })
                .catch((error) => {
                  this.$core.error(error, {
                    title: this.$t("dialog.title.error"),
                  });
                });
            })
            .catch(() => {
              //
            });
          break;
        case "force-delete":
          this.hasStore();
          await this.$core
            .confirm(
              this.$t("dialog.info.forceDelete", {
                module: this.$t(this.module.singular),
              }),
              {
                subTitle: this.relatedDeleteMessage
                  .replace(/:type/g, "this")
                  .replace(/:module/g, this.module.singular),
              }
            )
            .then(() => {
              this.store
                .forceDelete(props.row.id)
                .then(() => {
                  this.sendServerRequest();
                })
                .catch((error) => {
                  this.$core.error(error, {
                    title: this.$t("dialog.title.error"),
                  });
                });
            })
            .catch(() => {
              //
            });
          break;
        case "route":
          await this.$router.push({
            name: item.route,
            params: item.params ? item.params(props.row) : {},
            query: item.query ? item.query(props.row) : {},
          });
          break;
        default:
          if (typeof item.action === "function") {
            await item.action(props.row).catch(() => {
              //
            });
          }
          break;
      }

      this.innerLoading = undefined;
    },
    async onToolbarClicked(item, value) {
      console.func(
        "components/base/BaseTable:methods.onToolbarClicked()",
        arguments
      );
      if (item.key) {
        this.syncPagination[item.key] = value;
      }
      this.$emit("toolbar-clicked", item, value);

      // checked default actions status
      if (this.preventDefault) return false;

      switch (item.action) {
        case "delete":
          this.hasStore();
          this.$core
            .confirm(
              this.$t("dialog.info.deleteSelected", {
                module: this.$t(this.module.plural),
              })
            )
            .then(() => {
              this.store
                .deleteSelected(map(this.selected, "id"))
                .then(() => {
                  this.sendServerRequest();
                })
                .catch((error) => {
                  this.$core.error(error, {
                    title: this.$t("dialog.title.error"),
                  });
                });
            });
          break;
        case "restore":
          this.hasStore();
          this.$core
            .confirm(
              this.$t("dialog.info.restoreSelected", {
                module: this.$t(this.module.plural),
              })
            )
            .then(() => {
              this.store
                .restoreSelected(map(this.selected, "id"))
                .then(() => {
                  this.sendServerRequest();
                })
                .catch((error) => {
                  this.$core.error(error, {
                    title: this.$t("dialog.title.error"),
                  });
                });
            });
          break;
        case "force-delete":
          this.hasStore();
          this.$core
            .confirm(
              this.$t("dialog.info.forceDeleteSelected", {
                module: this.$t(this.module.plural),
              }),
              {
                subTitle: this.relatedDeleteMessage
                  .replace(/:type/g, "selected")
                  .replace(/:module/g, this.module.plural),
              }
            )
            .then(() => {
              this.store
                .forceDeleteSelected(map(this.selected, "id"))
                .then(() => {
                  this.sendServerRequest();
                })
                .catch((error) => {
                  this.$core.error(error, {
                    title: this.$t("dialog.title.error"),
                  });
                });
            });
          break;
        case "export":
          this.setExporting(true);
          await this.$core.exportTable({
            params: {
              ...this.syncPagination,
              rowsPerPage: this.syncPagination.rowsPerPage * 25,
              export: true,
            },
            data: this.rows,
            columns: this.columns.filter(
              (item) => !["actions"].includes(item.name)
            ),
            action: exportMethod(item, this.store),
            name: `table_${item.name || this.module.plural}`,
            type: item.type,
          });
          this.setExporting(false);
          break;
        case "route":
          this.$router.push({
            name: item.route,
            params: item.params,
            query: item.query,
          });
          break;
        case "filter":
          this.$refs.filter.show();
          break;
        case "request":
          this.sendServerRequest();
          break;
        default:
          if (typeof item.action === "function") {
            item.action({
              ...item,
              pagination: this.syncPagination,
              columns: this.columns,
              rows: this.rows,
            });
          }
          break;
      }
    },
    sendServerRequest() {
      this.$emit("update:selected", []);
      this.onRequest({
        pagination: this.syncPagination,
      });
    },
    showFilter() {
      console.func("components/base/BaseTable:methods.showFilter()", arguments);
      this.$refs.filter.show();
    },
    hasPermission(item, props = false) {
      // console.func('components/base/BaseTable:methods.hasPermission()', arguments)

      // permission doesn't exist
      if (this.noPermissions) return true;

      if (item.module) {
        return this.$app.hasModulePermission(item.module, item.permission);
      }

      if (item.condition && item.permission && props) {
        return (
          this.permissions.includes(item.permission) &&
          item.condition(
            { ...props.row, pagination: this.syncPagination },
            this.permissions
          )
        );
      } else if (item.condition && !item.permission && props) {
        return item.condition(
          { ...props.row, pagination: this.syncPagination },
          this.permissions
        );
      }

      return this.permissions.includes(item.permission) || !item.permission;
    },
    hasStore() {
      if (!this.store) return false;
    },
    canVisiable(column) {
      if (this.visibleColumns && this.visibleColumns.length) {
        return this.visibleColumns.includes(column);
      }
      return this.columns.filter((item) => item.name === column).length > 0;
    },
    setExporting(mode = true) {
      if (this.store) {
        this.store.setExporting(mode);
      }
    },
  },
  computed: {
    responsive() {
      return !this.noResponsive && this.$q.screen.lt.md;
    },
    hasFilter() {
      return this.filters.length;
    },
    actionItems() {
      const isDelete = this.syncPagination.deleted;
      return (this.actions || []).filter(
        ({ deleted }) => deleted === isDelete || deleted === "all"
      );
    },
    hasActionItems() {
      if (this.hasActions) return true;
      return (
        this.actionItems.filter((item) => this.hasPermission(item)).length > 0
      );
    },
    toolbarItems() {
      const isDelete = this.syncPagination.deleted;
      return (this.toolbar || []).filter(
        ({ deleted }) => deleted === isDelete || deleted === "all"
      );
    },
    permissions() {
      if (!this.module) return [];
      return this.$app.getPermissions(this.module.name);
    },
    tableSlots() {
      return Object.keys(this.$slots)
        .concat(this.columns.map((item) => "body-cell-" + item.name))
        .filter((item) => !exceptSlots.includes(item));
    },
    tableHeaderSlots() {
      return this.tableSlots.filter((item) => item.startsWith("header-cell"));
    },
    tableBodySlots() {
      return this.tableSlots.filter((item) => item.startsWith("body-cell"));
    },
    tableOtherSlots() {
      return this.tableSlots.filter((item) =>
        /^(?!header-cell)(?!body-cell).*/.test(item)
      );
    },
    topLeftWidth() {
      const width = 12 - this.topRightWidth;
      return width || 12;
    },
    canDelete() {
      return !this.syncPagination.deleted && this.selected.length > 0;
    },
    canRestore() {
      return this.syncPagination.deleted && this.selected.length > 0;
    },
  },
  mounted() {
    if (this.tableKey && this.$core.getPagination(this.tableKey)) {
      this.syncPagination = this.$core.getPagination(
        this.tableKey,
        this.pagination
      );
    }
    this.sendServerRequest();
  },
};
</script>
