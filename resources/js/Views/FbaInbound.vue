<template>
    <b-row>
        <div class="card card-transparent">
            <div class="card-header">
                <b-row>
                    <b-col>
                        <div class="card-title">FBA Inbound Shipments
                        </div>
                        <div class="card-controls float-right">
                            <b-button
                                size="sm"
                                variant="outline-secondary"
                                @click="importFbaInbounds()"
                            >
                                <em class="fas fa-cloud-download-alt mr-2"/> Sync FBA Plans
                            </b-button>
                            <b-link class="btn btn-outline-secondary"
                                    to="/fulfillments"
                            >
                                <em class="fas fa-chevron-double-left mr-2"/> Fulfillments
                            </b-link>
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
                        <template v-slot:cell(id)="data">
                            <b-link :to="`/fulfillments/inbound/${data.item.shipment_id}`">{{
                                    data.item.shipment_id
                                }}
                            </b-link>
                        </template>
                        <template v-slot:cell(name)="data">
                            {{ data.item.shipment_name }}
                        </template>
                        <template v-slot:cell(status)="data">
                            {{ data.item.shipment_status }}
                        </template>
<!--                        <template v-slot:cell(source)="data">-->
<!--                            {{ data.item.box_content_source }}-->
<!--                        </template>-->
                    </b-table>
                </div>
            </div>
        </div>
    </b-row>
</template>

<script>
import api from "../api";

export default {
    name: 'Dashboard',
    components: {},
    computed: {},
    data() {
        return {
            isBusy: false,
            table: {
                fields: [
                    {key: 'id', label: 'Shipment ID'},
                    {key: 'shipment_name', label: 'Shipment Name'},
                    {key: 'shipment_status', label: 'Status'},
                ],
                data: [],
                selected: []
            },
        }
    },
    methods: {
        async importFbaInbounds() {
            try {
                const response = await api.get('/services/fba/shipments/import');
                alert('Fba Inbound Shipments import queued.');
            } catch (error) {
                alert('Error while trying to queue `Fba Inbound Shipments`')
                throw new Error(error);
            }
        },
        async getFbaInboundOrders() {
            this.isBusy = true;
            try {
                const response = await api.get(`/services/fba/shipments/inbound`);

                this.table.data = response.data.shipments.data;

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
        this.getFbaInboundOrders();
    }
}
</script>
