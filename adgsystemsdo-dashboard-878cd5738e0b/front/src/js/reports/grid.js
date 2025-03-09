
export const baseOptions = {
  autoload: true,
  width: "100%",
  height: "690px",
  pageSize: 20,
  sorting: true,
  noDataContent: "No hay resultados.",
  paging: true,
  filtering: true,
  pagerFormat: "Páginas: {first} {prev} {pages} {next} {last}    {pageIndex} de {pageCount}",
  pagePrevText: "Ant.",
  pageNextText: "Prox.",
  pageFirstText: "Primera",
  pageLastText: "Última",
}

export function totalRow (totalCols) {
  return {
    onRefreshed: function(args) {
      const items = args.grid.option("data");
      const total = { Cobrador: "Totales"};
      totalCols.forEach(function (col) {
        total[col] = 0
      })

      items.forEach(function(item) {
        totalCols.forEach(function (col) {
          total[col] += Number(item[col].replace(/[^0-9\.]+/g,""))
        })
      });

      const $totalRow = $("<tr>").addClass("total-row");
      args.grid._renderCells($totalRow, total);
      args.grid._content.append($totalRow);
    }
  }
}