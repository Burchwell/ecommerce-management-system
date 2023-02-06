<template>
    <b-row class="m-0 p-0">
        <b-col cols="12" class="m-0 p-0">
            <b-row class="m-0 p-0">
                <b-col cols="12">
                    <div class="widget-11 widget-11-3 card no-border no-margin widget-loader-bar">
                        <b-row>
                            <b-col>
                                <div class="card-header">Product Inventory</div>
                                <div class="p-l-20 p-r-20 p-b-10 p-t-5">
                                    <div class="pull-left px-3">
                                        <h3 class="text-info">
                                            Inventory
                                        </h3>
                                        <div class="d-none d-md-block float-right">
                                            <!-- PDF -->
                                            <b-dropdown split text="Download PDF" class="m-2"
                                                        split-variant="outline-info" variant="secondary"
                                                        @click="exportPDF">
                                                <template v-slot:button-content>
                                                    <i class="fas fa-file-pdf mr-2"/> Download PDF
                                                </template>
                                                <b-dropdown-item v-if="table.selected.length > 0"
                                                                 @click="exportPDF('selected');"><i
                                                    class="far fa-file-pdf mr-2"/>Export Selected
                                                </b-dropdown-item>
                                            </b-dropdown>
                                            <!-- CSV -->
                                            <b-dropdown split text="Download CSV" class="m-2" split-variant="info"
                                                        variant="secondary" @click="exportCSV">
                                                <template v-slot:button-content>
                                                    <i class="fas fa-file-excel mr-2"/> Download CSV
                                                </template>
                                                <b-dropdown-item v-if="table.selected.length > 0"
                                                                 @click="exportCSV('selected');">Export Selected
                                                </b-dropdown-item>
                                            </b-dropdown>
                                            <b-button v-b-toggle.collapse-1 variant="info" size="sm"
                                                      class="d-sm-block d-md-none"><i class="fas fa-cog"/>
                                            </b-button>
                                            <b-collapse id="collapse-1" class="mt-2">
                                                <b-card>
                                                    <b-row>
                                                        <b-col class="p-0">
                                                            <b-form-group
                                                                id="input-group-1-collapse"
                                                                label="Download "
                                                                label-for="input-1"
                                                                description="Download PDF or CSV"
                                                            >
                                                                <b-button-group class="mx-1 btn-sm pl-0">
                                                                    <b-button size="sm" variant="secondary"
                                                                              style="max-height: 31px; margin-top: 4px;"
                                                                              @click="exportPDF">Download PDF
                                                                    </b-button>
                                                                    <b-button size="sm" @click="exportPDF('selected')"
                                                                              v-if="table.selected.length > 0"
                                                                              style="max-height: 31px; margin-top: 4px;">
                                                                        Selection
                                                                    </b-button>
                                                                </b-button-group>
                                                                <b-button-group class="mx-1 btn-sm pl-0">
                                                                    <download-csv
                                                                        style="max-height: 31px; margin-top: 4px; padding-top: 4px !important;"
                                                                        class="btn btn-secondary btn-sm pt-2"
                                                                        :data="table.data"
                                                                        :labels="table.labels"
                                                                        :name="`${filename}-full.csv`">
                                                                        Download CSV
                                                                    </download-csv>
                                                                    <download-csv
                                                                        v-if="table.selected.length > 0"
                                                                        style="max-height: 31px; margin-top: 4px; padding-top: 4px !important;"
                                                                        class="btn btn-secondary btn-sm pt-2"
                                                                        :data="exportSelected"
                                                                        :labels="table.labels"
                                                                        :name="`${filename}-selected.csv`">
                                                                        Selection
                                                                    </download-csv>
                                                                </b-button-group>
                                                            </b-form-group>
                                                        </b-col>
                                                    </b-row>
                                                    <b-row>
                                                        <b-col class="p-0">
                                                            <b-form-group
                                                                id="perPage-fg-collapse"
                                                                label="Per Page"
                                                                label-for="perPage"
                                                                description="Select Rows to show"
                                                            >
                                                                <b-form-select id="perPage"
                                                                               :options="pagination.options"
                                                                               v-model="pagination.perPage"/>
                                                            </b-form-group>

                                                        </b-col>
                                                        <b-col>
                                                            <b-form-group
                                                                id="pagination-fg"
                                                                label="Page"
                                                                label-for="pagination"
                                                                description="Select Page to"
                                                            >
                                                                <b-pagination id="pagination"
                                                                              :per-page="pagination.perPage"
                                                                              v-model="pagination.currentPage"
                                                                              :total-rows="table.data.length"/>
                                                            </b-form-group>
                                                        </b-col>
                                                    </b-row>
                                                </b-card>
                                            </b-collapse>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="widget-11-3-table tableFixHead p-3">
                                    <b-table class="table-condensed" hover :fields="table.columns" :items="this.table.data">
                                        <template slot="thead-top" slot-scope="data">
                                            <b-tr>
                                                <b-th role="columnheader" colspan="3" class="text-white text-center">&nbsp;</b-th>
                                                <b-th role="columnheader" colspan="4" class="text-info text-center">
                                                    Inventory <i class="fas fa-info-circle"
                                                                 v-b-popover.hover.top="iv_last_update"
                                                                 title="Last Update"/>
                                                </b-th>
                                                <b-th role="columnheader" colspan="4" class="text-center">
                                                    Turn Around <i class="fas fa-info-circle"
                                                                   v-b-popover.hover.top="iv_last_update"
                                                                   title="Last Update"/>
                                                </b-th>
                                            </b-tr>
                                        </template>
                                        <template v-slot:head(sku)="data">
                                            <b-form-input size="sm" v-model="search"
                                                          placeholder="SKU #"/>

                                        </template>
                                        <template v-slot:cell(sku)="data">
                                            <b-link class="text-info" :to="`/products/sku/${data.item.sku}`">
                                                {{ data.item.sku }}
                                            </b-link>
                                        </template>
                                        <template v-slot:head(index)="data">
                                            <b-form-checkbox v-model="selectAll" @click="selectAll"/>

                                        </template>
                                        <template v-slot:cell(index)="data">
                                            <b-form-checkbox name="product" v-model="table.selected"
                                                             :value="JSON.stringify(data.item)"/>
                                        </template>
                                        <template v-slot:head(mobile)="data">
                                            T |
                                            L |
                                            F
                                        </template>
                                        <template v-slot:cell(mobile_iv)="data">
                                            {{ data.item['iv_tampa'] }} | {{ data.item['iv_vegas'] }} |
                                            {{ data.item['iv_fba'] }}
                                        </template>
                                        <template v-slot:cell(mobile_iv)="data">
                                            {{ data.item['tampa'] }} | {{ data.item['vegas'] }} | {{ data.item['fba'] }}
                                        </template>
                                    </b-table>
                                </div>
                            </b-col>
                        </b-row>
                    </div>
                </b-col>
            </b-row>
            <b-row class="m-0 p-0">
                <b-col>
                    <div class="widget-11 widget-11-3 card no-border no-margin widget-loader-bar p-3">
                        <Pagination
                            :total-items="pagination.totalItems"
                            :per-page="pagination.perPage"
                            :current-page="pagination.currentPage"
                            v-on:emitChanges="updatePagination"
                        />
                    </div>
                </b-col>
            </b-row>
        </b-col>
    </b-row>
</template>
<script>
import api from "../../api";
import moment from 'moment-timezone';
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";
import Pagination from "../../components/Pagination";

export default {
    name: "Inventory",
    components: {Pagination},
    computed: {
        filtered() {
            let filter = this.table.search || ''
            this.fetchData();
        },
        filename() {
            const dt = new Date();
            return `${this.$options.name}-export-${dt.getFullYear()}-${dt.getMonth() > 10 ? dt.getMonth() : '0' + dt.getMonth()}-${dt.getDate() > 10 ? dt.getDate() : '0' + dt.getDate()}-${dt.getHours() + dt.getMinutes() + dt.getSeconds() + dt.getMilliseconds()}`.toLowerCase();
        },
        exportSelected() {
            let selected = [];
            for (let row of this.table.selected) {
                selected.push(JSON.parse(row));
            }
            return selected;
        },
        selectAll: {
            get() {
                return this.table.data ? this.table.selected.length === this.table.data.length : false;
            },
            set(value) {
                var selected = [];
                if (value) {
                    this.table.data.forEach(function (product) {
                        selected.push(JSON.stringify(product));
                    });
                }
                this.table.selected = selected;
            }
        }
    },
    watch: {
        search: function (val) {
            this.table.search = val;
            if (!this.awaitingSearch) {
                setTimeout(() => {
                    this.fetchData(val);
                    this.awaitingSearch = false;
                }, 700)
            }
            this.awaitingSearch = true;
        }
    },
    data() {
        return {
            ta_last_update: null,
            iv_last_update: null,
            awaitingSearch: false,
            search: '',
            table: {
                columns: [
                    {key: 'index', label: "", class: 'd-none d-md-table-cell'},
                    {key: 'sku', label: "SKU #", class: 'text-info w-sm-20', sortable: true},
                    {key: 'iv_total', label: "Total", class: 'text-center', sortable: true},
                    {key: 'iv_tampa', label: "Tampa", class: 'text-center d-none d-md-table-cell', sortable: true},
                    {key: 'iv_vegas', label: "Las Vegas", class: 'text-center d-none d-md-table-cell', sortable: true},
                    {key: 'iv_fba', label: "FBA", class: "text-center d-none d-md-table-cell", sortable: true},

                    {
                        key: 'ta_tampa',
                        label: "Tampa",
                        class: 'text-center d-none d-md-table-cell b-l b-1',
                        sortable: true
                    },
                    {key: 'ta_vegas', label: "Las Vegas", class: 'text-center d-none d-md-table-cell', sortable: true},
                    {key: 'ta_fba', label: "FBA", class: "text-center d-none d-md-table-cell", sortable: true},

                    {key: 'mobile_iv', label: "T | L | F", class: "text-center d-sm-table-cell d-md-none"},
                    {key: 'mobile_ta', label: "T | L | F", class: "text-center d-sm-table-cell d-md-none"},
                ],
                data: [],
                selected: [],
                search: "",
            },
            pagination: {
                currentPage: 1,
                perPage: 25,
                totalItems: 0,
                options: [
                    {value: 25, text: 25},
                    {value: 50, text: 50},
                    {value: 100, text: 100},
                    {value: 200, text: 200},
                ]
            }
        }
    },
    methods: {
        // TODO: update to match fulfillments
        async fetchData(search) {
            try {
                let response = await api.get(`/products/inventory?page=${this.pagination.currentPage}&perPage=${this.pagination.perPage}&search=${search || ''}`);
                this.table.data = response.data.products.data.map(product => {
                    let total = 0;
                    product.inventory.map(wh => {
                        product['iv_' + wh.warehouse.slug] = wh.quantity || 0;
                        total += wh.quantity;
                        product['iv_updated_at'] = wh.updated_at;
                    });

                    product.turn_around.map(ta => {
                        product['ta_' + ta.warehouse.slug] = ta.quantity || 0;
                        product['ta_updated_at'] = ta.updated_at;
                    });

                    product['id'] = product['id'];
                    product['iv_total'] = total;
                    product['iv_tampa'] = product['iv_TPA WH1'] || 0;
                    product['iv_vegas'] = product['iv_LV'] || 0;
                    product['iv_fba'] = product['iv_FBA'] || 0;
                    product['ta_tampa'] = product['ta_TPA WH1'] || 0;
                    product['ta_vegas'] = product['ta_LV'] || 0;
                    product['ta_fba'] = product['ta_FBA'] || 0;
                    delete product['iv_TPA WH1'];
                    delete product['iv_LV'];
                    delete product['iv_FBA'];
                    delete product['ta_TPA WH1'];
                    delete product['ta_LV'];
                    delete product['ta_FBA'];
                    delete product.inventory;
                    delete product.turn_around;
                    return product;
                });
                this.ta_last_update = moment(this.table.data.map(function (e) {
                    return e.ta_updated_at;
                }).sort().reverse()[0]).format('LLLL');
                this.iv_last_update = moment(this.table.data.map(function (e) {
                    return e.iv_updated_at;
                }).sort().reverse()[0]).format('LLLL');

                this.pagination.perPage = parseInt(response.data.products.per_page);
                this.pagination.totalItems = parseInt(response.data.products.total);
                this.pagination.currentPage = parseInt(response.data.products.current_page);
            } catch (err) {
                console.log(err);
            }
        },
        updatePagination(value) {
            this.pagination = value;
            this.fetchData()
        },
        async exportPDF(type) {
            const doc = new jsPDF();
            let data = type === 'selected' ? this.table.selected.map(row => JSON.parse(row)) : this.table.data;
            autoTable(doc, {
                body: data,
                columns: [
                    {header: '#', dataKey: 'id'},
                    {header: 'SKU', dataKey: 'sku'},
                    {header: 'Total', dataKey: 'total'},
                    {header: 'Tampa', dataKey: 'tampa'},
                    {header: 'Las Vegas', dataKey: 'vegas'},
                    {header: 'FBA', dataKey: 'fba'},
                ],
                headStyles: {
                    fillColor: [0, 0, 0],
                    textColor: [255, 255, 255],
                    lineColor: [255, 255, 255],
                    lineWidth: 0.1
                },
                theme: 'striped',
                didParseCell: function (row) {
                    if (row.cell.section === 'body' && row.row.index % 2 === 0) {
                        row.cell.styles.fillColor = 235
                    }
                    row.cell.styles.valign = 'middle';
                    row.cell.styles.lineWidth = 0.1;
                    row.cell.styles.lineColor = [222, 226, 230];
                    if (row.column.dataKey === 'sku') {
                        row.cell.styles.halign = "left"
                    } else {
                        row.cell.styles.halign = "center"
                    }
                },
                didDrawPage: function () {
                    const logo = "data:image/jpeg;base64,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";
                    // Header
                    doc.setFontSize(12);
                    var fileTitle = "Inventory Report";
                    doc.text(fileTitle, 14, 35);
                    doc.addImage(logo, 'JPEG', 10, 10, 30, 10);
                },
                margin: {
                    bottom: 60, //this decides how big your footer area will be
                    top: 40 //this decides how big your header area will be.
                }
            });
            doc.save(`${this.filename}.pdf`);
        },
        exportCSV(type) {
            let columns = [
                {header: '#', dataKey: 'id', type: 'Int'},
                {header: 'SKU', dataKey: 'sku', type: 'String'},
                {header: 'Total', dataKey: 'total', type: 'Int'},
                {header: 'Tampa', dataKey: 'tampa', type: 'Int'},
                {header: 'Las Vegas', dataKey: 'vegas', type: 'Int'},
                {header: 'FBA', dataKey: 'fba', type: 'Int'},
            ];
            let data = type === 'selected' ? this.table.selected.map(row => JSON.parse(row)) : this.table.data;

            var csv = data.map(function (row) {
                let csvRow = columns.map((fields, index) => {
                    if (columns[index].type === "Int") {
                        return parseInt(row[columns[index].dataKey]) || 0
                    } else if (columns[index].type === "String") {
                        return row[columns[index].dataKey] || ''
                    }
                });

                return csvRow.join(',');
            });

            let blob = new Blob([columns.map((column => column.header)).join(',') + '\r\n' + csv.join('\r\n')], {type: 'text/csv'});
            const link = document.createElement('a')
            link.href = URL.createObjectURL(blob)
            link.download = this.filename + '.csv'
            link.click()
            URL.revokeObjectURL(link.href)
        },
    },
    mounted() {
        this.fetchData();
    }
}
</script>

<style scoped lang="scss">

</style>
