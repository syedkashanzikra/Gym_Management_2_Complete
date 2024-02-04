export function RowsPlugin({ store }) {
  return {
    rows: [],
    exporting: false,
    setRows(payload) {
      if (store.exporting) return false;
      store.rows = payload;
    },
    pushRows(payload) {
      store.rows.push(payload);
    },
    addRows(payload) {
      store.rows.unshift(payload);
    },
    editRows(payload) {
      const item = store.rows.find((item) => item.id === payload.id);
      Object.assign(item, payload);
    },
    updateRowByIndex(payload, index) {
      const item = store.rows[index];
      Object.assign(item, payload);
    },
    deleteRows(payload) {
      const index = store.rows.findIndex((element) => {
        return element.id === payload;
      });
      store.rows.splice(index, 1);
    },
    setExporting(payload) {
      store.exporting = payload;
    },
  };
}
