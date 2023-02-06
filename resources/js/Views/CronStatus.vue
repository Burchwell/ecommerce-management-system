<template>
    <b-row>
        <div class="card card-transparent">
            <div class="card-header">
                <b-row>
                    <b-col>
                        <div class="card-title">Cron Status
                        </div>
                        <div class="card-controls float-right">
                            <ul style="list-style: none">
                                <li><a href="#" class="card-refresh" data-toggle="refresh"><i class="fas fa-redo-alt"/></a>
                                </li>
                            </ul>
                        </div>
                    </b-col>
                </b-row>
            </div>
            <div class="card-body d-flex flex-wrap">
                <div class="col-lg-12 sm-no-padding">
                    <b-table striped
                             :items="table.data"
                             :fields="table.fields"
                             items-per-page-select
                             :items-per-page="15"
                             responsive="sm"
                             :busy="isBusy"

                    >
                        <template v-slot:table-busy>
                            <div class="text-center text-danger my-2">
                                <b-spinner class="align-middle"></b-spinner>
                                <strong>Loading...</strong>
                            </div>
                        </template>
                        <template v-slot:head(index)="data">
                            <b-form-checkbox v-model="selectAll" @click="selectAll"/>
                        </template>
                        <template v-slot:cell(index)="data">
                            <b-form-checkbox name="product" v-model="table.selected" :value="data.item.id"/>
                        </template>
                        <template v-slot:cell(name)="data">
                            <a v-bind:href="'https://cronitor.io/dashboard?search='+data.item.code" target="_blank">{{ data.item.name }}</a>
                        </template>
                        <template v-slot:cell(type)="data">
                            {{ data.item.type }}
                        </template>
                        <template v-slot:cell(last_run_at)="data">
                           {{ data.item.last_run_at }}
                        </template>
                        <template v-slot:cell(is_passing)="data">
                            <b-badge variant="success" v-if="data.item.is_passing === true">
                                Up
                            </b-badge>
                            <b-badge variant="danger" v-else>Down</b-badge>
                        </template>
                        <template #show_details="{item, index}">
                            <td class="py-2">
                                <b-button
                                    color="primary"
                                    variant="outline"
                                    square
                                    size="sm"
                                    @click="$router.push('/product/' + item.name)"
                                    class="float-right"
                                >
                                    View
                                </b-button>
                            </td>
                        </template>
                    </b-table>
                </div>
            </div>
        </div>
    </b-row>
</template>

<script>
    import api from "../api";

    export default {
        name: 'CronStatus',
        components: {},
        computed: {
            selectAll: {
                get() {
                    return this.table.data ? this.table.selected.length === this.table.data.length : false;
                },
                set(value) {
                    var selected = [];
                    if (value) {
                        this.table.data.forEach(function (data) {
                            selected.push(data.code);
                        });
                    }
                    this.table.selected = selected;
                }
            }
        },
        data() {
            return {
                isBusy: false,
                table: {
                    fields: [
                        {key: 'name', label: 'Name'},
                        {key: 'type', label: 'Type'},
                        {key: 'last_run_at', label: 'Last Seen'},
                        {key: 'is_passing', label: 'Status'},
                    ],
                    data: [],
                    selected: []
                },
            }
        },
        methods: {
            async getCronStatus() {
                this.isBusy = true;
                try {
                    const response = await api.get(`/services/cronitor`);

                    this.table.data = response.data.monitors;

                } catch (err) {
                    console.log(err);
                } finally {
                    this.isBusy = false;
                }
            },
            toggleBusy() {
                this.isBusy = !this.isBusy
            }
        },
        mounted() {
            this.getCronStatus();
        }
    }
</script>
