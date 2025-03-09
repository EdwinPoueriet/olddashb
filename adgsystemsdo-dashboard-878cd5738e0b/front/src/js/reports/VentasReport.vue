<template>
  <div>
    <div class="row">
      <div class="col-xs-12">
        <div class="main-box">
          <div class="main-box-header clearfix">
            <div class="row">
              <div class="col-sm-2">
                <h2 style="color: #03a9f4; font-weight: 500" class="">
                  Filtros
                </h2>
              </div>
              <div class="col-sm-10">
                <form
                  :action="'/legacy/print/ventas' + tipo + ''"
                  method="post"
                  target="_blank"
                >
                  <template v-if="showPrint">
                    <input type="hidden" :value="rango" name="rango" />
                    <input type="hidden" :value="vendedor" name="vendedor" />
                    <input type="hidden" name="order_by" v-model="orden" />
                    <input
                      type="hidden"
                      name="order_method"
                      v-model="orden_method"
                    />
                    <div class="col-sm-5">
                      <div class="filter-value-container form-inline">
                        <label>Filtrar Compañia</label>
                        <select
                          name="company"
                          class="form-control"
                          v-model="selectedCompany"
                        >
                          <option value="consolidado">
                            Reporte Consolidado
                          </option>
                          <option
                            v-for="company in companies"
                            :value="company.company_id"
                          >
                            {{ company.company_id }} -
                            {{ company.company_name }}
                          </option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-5">
                      <div
                        v-if="selectedCompany == 'consolidado'"
                        class="filter-value-container form-inline"
                      >
                        <label>Vista en Reporte</label>
                        <select
                          name="consolidado_group"
                          class="form-control"
                          v-model="consolidado_group"
                        >
                          <option value="consolidado">
                            Agrupados en Misma Lista
                          </option>
                          <option value="separado">
                            Separados por Compañia
                          </option>
                        </select>
                      </div>
                      <i
                        @click="showPrint = false"
                        style="position: absolute; right: 10px; top: 10px"
                        class="fa fa-eye-slash"
                      ></i>
                    </div>
                    <div class="col-sm-2">
                      <button
                        style="margin-left: 5px"
                        type="submit"
                        class="btn btn-success pull-right"
                      >
                        <i class="fa fa-print fa-fw"></i>
                        Imprimir
                      </button>
                    </div>
                  </template>
                </form>
                <button
                  v-if="!showPrint"
                  @click="showPrint = true"
                  style="margin-left: 5px"
                  class="btn btn-success pull-right"
                >
                  <i class="fa fa-print fa-fw"></i>
                  Imprimir
                </button>
              </div>
            </div>
          </div>
          <hr style="margin-top: 0; margin-bottom: 5px" />
          <div class="main-box-body">
            <div class="row" style="padding: 20px">
              <div class="col-xs-12">
                <div class="row">
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label>Tipos de Reportes</label>
                      <div
                        class="filter-value-container"
                        style="margin-top: 3px"
                      >
                        <select name="tipo" class="form-control" v-model="tipo">
                          <option value="detallado">Detallado</option>
                          <option value="compacto">Resumido</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label>Rango de Fecha</label>
                      <div
                        class="filter-range-container"
                        style="margin-top: 3px"
                      >
                        <div class="input-group">
                          <span class="input-group-addon"
                            ><i class="fa fa-calendar"></i
                          ></span>
                          <input
                            autocomplete="off"
                            type="text"
                            class="form-control"
                            id="date-input"
                            name="rango"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <div
                        v-if="
                          vendedores_inactivos !== null &&
                          vendedores_inactivos.length > 0
                        "
                        class="checkbox"
                        style="float: right; margin: 0"
                      >
                        <label
                          ><input
                            v-model="showInactiveSellers"
                            style="bottom: 4px"
                            type="checkbox"
                            value=""
                          />Mostrar Inactivos</label
                        >
                      </div>
                      <label>Vendedores</label>
                      <div
                        class="filter-value-container"
                        style="margin-top: 3px"
                      >
                        <select
                          name="vendedor"
                          @change="generarClicked()"
                          class="form-control"
                          v-model="vendedor"
                        >
                          <option value="todos">Todos</option>

                          <option
                            v-for="vendedor in vendedores"
                            :value="vendedor.seller_code"
                          >
                            {{ vendedor.seller_code }} -
                            {{ vendedor.seller_name }}
                          </option>

                          <option
                            v-for="vendedor in vendedores_inactivos"
                            v-if="showInactiveSellers"
                            style="background-color: #f2dede"
                            :value="vendedor.seller_code"
                          >
                            {{ vendedor.seller_code }} -
                            {{ vendedor.seller_name }}
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row" style="background: antiquewhite">
                  <div class="col-sm-3">
                    <span class="total-header">Total Transacciones:</span>
                    {{ totales.ventas }}
                  </div>
                  <div class="col-sm-3">
                    <span class="total-header">Total Bruto:</span> RD${{
                      formatMoney(totales.bruto)
                    }}
                  </div>
                  <div class="col-sm-3">
                    <span class="total-header">Total Descuentos:</span> RD${{
                      formatMoney(totales.descuento)
                    }}
                  </div>
                  <div class="col-sm-3">
                    <span class="total-header">Total Impuestos:</span> RD${{
                      formatMoney(totales.impuestos)
                    }}
                  </div>
                  <div class="col-sm-3">
                    <span class="total-header">Total General:</span> RD${{
                      formatMoney(totales.total)
                    }}
                  </div>
                </div>
                <!--<table class="table">-->
                <!--<tr style="    background: antiquewhite;">-->
                <!--<td><span class="total-header">Total Transacciones:</span></td>-->
                <!--<td><span class="total-value">{{ totales.ventas }}</span></td>-->
                <!--<td><span class="total-header">Total Bruto:</span></td>-->
                <!--<td><span class="total-value">{{formatMoney(totales.bruto)}}</span></td>-->
                <!--<td><span class="total-header">Total Descuentos:</span></td>-->
                <!--<td><span class="total-value">{{formatMoney(totales.descuento)}}</span></td>-->
                <!--<td><span class="total-header">Total Impuestos</span></td>-->
                <!--<td><span class="total-value">{{formatMoney(totales.impuestos)}}</span></td>-->
                <!--<td><span class="total-header">Total General:</span></td>-->
                <!--<td><span class="total-value">{{formatMoney(totales.total)}}</span></td>-->
                <!--</tr>-->
                <!--</table>-->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="main-box" style="padding-top: 20px">
          <template v-if="loading">
            <div style="text-align: center">
              <i style="font-size: 30px" class="fa fa-spinner fa-spin"></i>
            </div>
          </template>
          <div v-show="!hideTableOp" class="main-box-body">
            <div class="tablewrapper">
              <div id="jsGrid"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
.total-header {
  font-weight: 600;
}
.jsgrid-cell {
  overflow: hidden;
}
</style>
<script>
import { baseOptions, totalRow } from "./grid";
import {
  sortNumber,
  moneyToNumber,
  headerRight,
  formatMoney,
} from "./../common";
require("./gridsetup");
export default {
  data() {
    return {
      currentSorting: {
        field: null,
        order: null,
      },
      showPrint: false,
      count: 0,
      hideTableOp: false,
      totales: {
        bruto: 0,
        descuento: 0,
        impuestos: 0,
        total: 0,
        ventas: 0,
      },
      loading: false,
      showInactiveSellers: false,
      vendedores_inactivos: sellersListInactive,
      orden: "order_date",
      orden_method: "desc",
      tipo: "detallado",
      vendedores: sellersList,

      companies: companyList,
      rango:
        moment().format("YYYY-MM-DD") + " - " + moment().format("YYYY-MM-DD"),
      selectedCompany: "consolidado",
      consolidado_group: "consolidado",
      vendedor: "todos",
    };
  },
  watch: {
    currentSorting(value) {
      if (value && value.field && value.order) {
        this.orden_method = value.order;
        if (mapping[value.field]) this.orden = mapping[value.field];
        else this.orden = value.field;
      }
    },
    tipo(val) {
      if (val === "compacto") {
        this.orden = "seller_code";
      } else {
        this.orden = "order_date";
      }
      this.generarClicked();
    },
  },
  methods: {
    generarClicked() {
      this.fetchReportData();
    },
    fetchReportData() {
      this.loading = true;
      $.ajax({
        method: "POST",
        url: "/reports/ventas",
        data: {
          order_by: this.orden,
          order_method: this.orden_method,
          tipo: this.tipo,
          rango: this.rango,
          company: this.selectedCompany,
          consolidado_group: this.consolidado_group,
          vendedor: this.vendedor,
        },
        success: (data) => {
          if (!(data instanceof Object)) {
            data = JSON.parse(data);
          }
          // this.$emit('loading', false)

          this.updateTable(data, this.tipo, this.consolidado_group);
          // this.$emit('updateTableData', )
        },
        complete: () => {
          this.loading = false;
        },
      });
    },

    hideTable(val) {
      this.hideTableOp = val;
    },
    formatMoney: formatMoney,

    setSortingParams(sorting) {
      this.currentSorting = sorting;
    },

    updateTable(data, type, agrupacion) {
      let result = null;
      let realdata = null;
      if (type === "detallado") {
        realdata = data;
        result = this.loadDetallado(realdata, agrupacion);
      } else {
        realdata = data.data;
        result = this.loadResumido(realdata, agrupacion);
      }

      this.totales = this.calcularTotales(type, realdata);
      this.updateTable2(result.options, result.cantidad);
    },
    updateTable2(options, cantidad) {
      this.cantidad = cantidad;
      const grid = $("#jsGrid");
      grid.jsGrid("destroy");
      grid.jsGrid({
        ...options,
        width: "100%",
        onRefreshed: () => {
          const sorting = grid.jsGrid("getSorting");
          this.setSortingParams(sorting);
        },
      });
      grid.jsGrid("option", "filtering", false);
    },
    changeOrdenStatus(orden, status) {
      console.log(orden);
    },
    loadDetallado(data, agrupacion) {
      let options = {};
      let cantidad = 0;

      console.log(data);

      cantidad = data.length;
      options = {
        ...baseOptions,
        controller: {
          loadData: function (filter) {
            return data.filter((val) => {
              return (
                val.seller
                  .toLowerCase()
                  .includes(filter.seller.toLowerCase()) &&
                val.customer
                  .toLowerCase()
                  .includes(filter.customer.toLowerCase())
              );
            });
          },
        },

        fields: [
          {
            name: "status",
            headerTemplate: function () {
              return '<i class="fa fa-cloud"></i>';
            },
            // itemTemplate: function (item) {
            //   return item === "1" ? "h" : "";
            // },
            // type: "status",
            // type: "text",
            itemTemplate: function (value, item) {
              var newVAlue = item.status;
              var editBt = $(
                newVAlue == "1"
                  ? '<input class="jsgrid-button jsgrid-update-button" type="button" tittle="edit">'
                  : '<input class="jsgrid-button" type="button" tittle="edit">'
              ).on("click", function (e) {
                var r = confirm("Esta seguro de querer cambiar este valor?");
                if (r == true) {
                  $.ajax({
                    method: "POST",
                    url: "/reports/ventas/change/status",
                    data: {
                      id: item.order_id,
                      status: item.status == "1" ? 0 : 1,
                    },
                    success: (data) => {
                      alert(
                        "El cambio se efectuo con exito, los cambios se presentaran cuando se refresquen los datos!!"
                      );
                    },
                    error: () => {
                      console.log(
                        "error de base de datos con la orden: " + item.order_id
                      );
                    },
                  });
                }
              });
              return editBt;
            },
            width: 20,
            filtering: false,
          },

          {
            name: "Compañia",
            type: "date",
            width: 60,
            filtering: true,
            visible: agrupacion === "separado",
          },

          {
            name: "order_date_time",
            title: "Fecha",
            type: "text",
            width: 90,
            filtering: true,
            itemTemplate: function (item) {
              return moment(item).format("DD-MM-YYYY h:mm A");
            },
          },

          {
            name: "seller",
            title: "Vendedores",
            type: "text",
            width: 160,
            filtering: true,
          },

          {
            name: "customer",
            title: "Clientes",
            type: "text",
            width: 180,
            filtering: true,
          },

          {
            name: "order_reference",
            title: "No. Orden",
            type: "text",
            width: 80,
            filtering: true,
            sorter: "money",
          },

          // { name: "order_code",title:'Código ERP', type: "text" , width: 50,  filtering: true, sorter: "money"},

          // { name: "order_gross_amount", type: "text", width: 60, filtering: false, align: "right", sorter: "money",
          //   headerTemplate: headerRight('Bruto')},

          // { name:'order_discount_amount', type: "text", width: 60, filtering: false,align: "right", sorter: "money",
          //   headerTemplate: headerRight('Descuento') },

          {
            name: "order_tax_amount",
            type: "text",
            width: 90,
            filtering: false,
            align: "right",
            sorter: "money",
            headerTemplate: headerRight("Impuestos"),
          },

          {
            name: "total",
            type: "text",
            width: 80,
            filtering: false,
            align: "right",
            sorter: "money",
            headerTemplate: headerRight("Total"),
          },
          {
            name: "in_location",
            type: "text",
            width: 50,
            align: "right",
            sorting: false,
            headerTemplate: function () {
              return '<i style="font-size: 16px" class="fa fa-map-marker"></i>';
            },
          },
          {
            name: "view_location",
            type: "view_location",
            width: 50,
            align: "right",
            sorting: false,
            title: "Ver loc.",
          },
          {
            title: "Detalle",
            name: "order_id",
            type: "order_detail",
            width: 60,
            filtering: false,
            sorting: false,
          },
          {
            type: "control",
            headerTemplate: function () {
              return this._createOnOffSwitchButton(
                "filtering",
                this.searchModeButtonClass,
                false
              );
            },
            searchButtonTooltip: "Buscar",
            clearFilterButtonTooltip: "Limpiar Filtros",
            inserting: false,
            editing: false,
            filtering: true,
            editButton: false,
            deleteButton: false,
          },
        ],
      };
      return { options, cantidad };
    },

    loadResumido(data) {
      let options = {};
      let cantidad = 0;
      cantidad = data.length;
      options = {
        ...baseOptions,
        controller: {
          loadData: function (filter) {
            return data.filter((val) =>
              val.Vendedor.toLowerCase().includes(filter.Vendedor.toLowerCase())
            );
          },
        },
        fields: [
          { name: "Vendedor", type: "text", width: 200, filtering: true },
          // { name: "cantidad",title:'Cantidad', type: "text", width: 50,  filtering: false},

          {
            name: "Ventas",
            type: "text",
            width: 100,
            filtering: false,
            align: "right",
            sorter: "money",
            headerTemplate: headerRight("Ventas"),
          },
          {
            name: "Bruto",
            type: "text",
            width: 100,
            filtering: false,
            align: "right",
            sorter: "money",
            headerTemplate: headerRight("Bruto"),
          },
          {
            name: "Descuento",
            type: "text",
            width: 100,
            filtering: false,
            align: "right",
            sorter: "money",
            headerTemplate: headerRight("Descuento"),
          },
          {
            name: "Impuesto",
            type: "text",
            width: 100,
            filtering: false,
            align: "right",
            sorter: "money",
            headerTemplate: headerRight("Impuesto"),
          },
          {
            name: "Total",
            type: "text",
            width: 100,
            filtering: false,
            align: "right",
            sorter: "money",
            headerTemplate: headerRight("Total"),
          },
          {
            type: "control",
            headerTemplate: function () {
              return this._createOnOffSwitchButton(
                "filtering",
                this.searchModeButtonClass,
                false
              );
            },
            searchButtonTooltip: "Buscar",
            clearFilterButtonTooltip: "Limpiar Filtros",
            inserting: false,
            editing: false,
            filtering: true,
            editButton: false,
            deleteButton: false,
          },
        ],
      };
      return { options, cantidad };
    },

    calcularTotales(type, data) {
      const totales = {
        bruto: 0,
        descuento: 0,
        impuestos: 0,
        total: 0,
        ventas: 0,
      };

      if (type === "detallado") {
        totales.ventas = data.length;
        data.forEach((val) => {
          totales.bruto += moneyToNumber(val.order_gross_amount);
          totales.descuento += moneyToNumber(val.order_discount_amount);
          totales.impuestos += moneyToNumber(val.order_tax_amount);
          totales.total += moneyToNumber(val.total);
        });
      } else {
        data.forEach((val) => {
          totales.bruto += moneyToNumber(val.Bruto);
          totales.descuento += moneyToNumber(val.Descuento);
          totales.ventas += parseInt(val.Ventas);
          totales.impuestos += moneyToNumber(val.Impuesto);
          totales.total += moneyToNumber(val.Total);
        });
      }

      return totales;
    },
    setupBasico() {
      let date = $("#date-input");
      date.daterangepicker({
        applyClass: "btn-primary",
        startDate: moment(),
        ranges: {
          Hoy: [moment(), moment()],
          Ayer: [moment().subtract(1, "days"), moment().subtract(1, "days")],
          "Ultimos 7 dias": [moment().subtract(6, "days"), moment()],
          "Ultimos 30 dias": [moment().subtract(29, "days"), moment()],
          "Este mes": [moment().startOf("month"), moment().endOf("month")],
          "Mes anterior": [
            moment().subtract(1, "month").startOf("month"),
            moment().subtract(1, "month").endOf("month"),
          ],
        },
        locale: {
          format: "YYYY-MM-DD",
          applyLabel: "Aceptar",
          cancelLabel: "Borrar",
          fromLabel: "Desde",
          toLabel: "Hasta",
          customRangeLabel: "Personalizado",
          daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
          monthNames: [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre",
          ],
          firstDay: 1,
        },
      });
      date.on("apply.daterangepicker", (ev, picker) => {
        this.rango =
          picker.startDate.format("YYYY-MM-DD") +
          " - " +
          picker.endDate.format("YYYY-MM-DD");
        this.generarClicked();
      });
    },
  },
  mounted() {
    this.setupBasico();
    // $('.boxi').matchHeight();
    this.generarClicked();
  },
};
</script>