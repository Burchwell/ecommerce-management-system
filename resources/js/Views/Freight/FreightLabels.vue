<template>
    <b-row class="mt-5">
        <b-col lg="6" cols="12">
            <b-card class="card-transparent">
                <b-card-header>
                    <b-card-title>New Freight Label</b-card-title>
                </b-card-header>
                <b-card-body>
                    <h3>Create Freight Labels for Print</h3>
                    <p>Use this form to create a Freight Label PDF and print it the labels on a label printer.</p>
                    <br/>
                    <p class="small hint-text mt-5"></p>
                </b-card-body>
            </b-card>
        </b-col>
        <b-col lg="6" cols="12">
            <b-card>
                <b-card-header>
                    <b-card-title>Freight Label Form</b-card-title>
                </b-card-header>
                <b-card-body>
                    <form id="form-project" role="form" autocomplete="off" novalidate="novalidate">
                        <h3 class="mw-80">Fill out the form below</h3>
                        <p class="mw-80 m-b-25">Once you have filled out the form, hit `Create Labels`
                        </p>
                        <p>Basic Information</p>
                        <div class="form-group-attached">
                            <div class="form-group form-group-default required" aria-required="true">
                                <label>Freight Carrier</label>
                                <b-select v-model="label.carrier">
                                    <b-select-option value="" selected>Select Freight Carrier</b-select-option>
                                    <b-select-option value="AAA Cooper Freight">AAA Cooper Freight</b-select-option>
                                    <b-select-option value="Central Transport">Central Transport</b-select-option>
                                    <b-select-option value="Estes">Estes</b-select-option>
                                    <b-select-option value="FTL (AZNG)">Full Truck - Relay Load Board</b-select-option>
                                    <b-select-option value="RoadRunner">RoadRunner</b-select-option>
                                    <b-select-option value="YRC">YRC</b-select-option>
                                </b-select>
                            </div>
                        </div>
                        <div class="form-group-attached">
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <label>Vendor</label>
                                    <b-form-input type="text" v-model="label.vendor" placeholder="Vendor Name"/>
                                </div>
                            </div>
                        </div>
                        <p>Freight Details</p>
                        <div class="row clearfix">
                            <div class="col-md-6">
                                <div class="form-group form-group-default required" aria-required="true">
                                    <label>Pickup Date</label>
                                    <b-form-input type="date" class="form-control" v-model="label.pickup_date" required=""
                                                  aria-required="true"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-group-default">
                                    <label>PO Number</label>
                                    <b-form-input type="text" class="form-control" v-model="label.po_number" placeholder="PO#"
                                                  required="" aria-required="true"/>
                                </div>
                            </div>
                        </div>
                        </div>
                        <p class="m-t-10">Destination</p>
                        <div class="form-group-attached">
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <b-form-textarea name="address" rows="4" v-model="label.address"/>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="form-group-attached">
                            <div class="row">
                                <div class="col-8">
                                    <div class="form-group form-group-default float-left">
                                        <label>Pallets</label>
                                        <b-form-input type="number" class="form-control" v-model="label.pallets"
                                                      placeholder="#"
                                                      required="" aria-required="true" style="width: 90px;"/>
                                    </div>
                                    <div class="form-group form-group-default float-left ml-4">
                                        <label>Labels p. Pallet</label>
                                        <b-form-input type="number" class="form-control" v-model="label.labels"
                                                      placeholder="#"
                                                      required="" aria-required="true" style="width: 90px;"/>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group form-group-default">
                                        <label class="py-4"><br/></label>
                                        <br/>
                                        <b-button variant="primary" class="pull-right mt-2 " type="button" @click="submit()" style="
    float: right;
    padding: 10px;
    width: 100%;
">Create Label
                                        </b-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </b-card-body>
            </b-card>
        </b-col>
    </b-row>
</template>

<script>
import jsPDF from 'jspdf';
import api from "../../api";

export default {
    name: "FreightLabels",
    data() {
        return {
            label: {
                carrier: '',
                vendor: '',
                pickup_date: '',
                po_number: '',
                address: '',
                pallets: '',
                labels: 4
            },
            errors: {}
        }
    },
    methods: {
        async submit() {
            this.errors = {};
            try {
                const response = await api.post('/orders/freight/label', this.label, {
                    responseType: 'arraybuffer',
                    headers: {
                        'Accept': 'application/octet-stream',
                    }
                });
                console.log(response);
                const fileName = 'test.pdf'
                var file = new Blob([response.data], {type: "application/pdf"});
                var a = document.createElement("a");
                a.href = URL.createObjectURL(file);
                a.download = fileName;
                document
                    .body
                    .appendChild(a);
                a.click();
            } catch (error) {
                console.log(error)
            }
        }
    }
}
</script>

<style scoped>

</style>
