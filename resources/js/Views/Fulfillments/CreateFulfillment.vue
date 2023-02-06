<template>
    <b-card v-if="product.local_db">
        <b-card-header>
            <h3>{{ sku }}</h3>
        </b-card-header>
        <b-card-body>
            <b-row>
                <b-col cols="12" md="3">
                    <b-form-group
                        id="SKUfg"
                        description="Stock Keep Unit"
                        label="SKU"
                        label-for="sku"
                    >
                        <b-form-input id="sku" v-model="product.local_db.sku" trim disabled/>
                    </b-form-group>
                </b-col>
                <b-col cols="12" md="3">
                    <b-form-group
                        id="Typefg"
                        description="Stock Keeping Unit"
                        label="Product Type"
                        label-for="type"
                    >
                        <b-form-input id="type" v-model="product.local_db.product_type || ''" trim disabled/>
                    </b-form-group>
                </b-col>
                <b-col cols="12" md="2">
                    <b-form-group
                        id="UPCfg"
                        description="Universal Product Code"
                        label="UPC"
                        label-for="upc"
                    >
                        <b-form-input id="upc" v-model="product.local_db.upc" trim disabled/>
                    </b-form-group>
                </b-col>
                <b-col cols="12" md="2">
                    <b-form-group
                        id="ASINfg"
                        description="Universal Product Code"
                        label="ASIN"
                        label-for="ASIN"
                    >
                        <b-form-input id="asin" v-model="product.local_db.asin" trim disabled/>
                    </b-form-group>
                </b-col>
                <b-col cols="12" md="1" offset-md="1">
                    <b-form-group
                        id="Active"
                        description="Click to activate or deactive"
                        label="Active"
                    >
                        <b-button :variant="active[this.product.local_db.active].variant" size="small"
                                  @click="toggleOption"
                        >{{ active[this.product.local_db.active].text }}
                        </b-button>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col cols="12" md="6" class="mt-5">
                    <b-row class="p-0 m-0">
                        <b-col class="p-0 m-0 pl-1">
                            <h4>Item</h4>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12">
                            <b-img-lazy :src="itemImageUrl(420, 420)" />
                        </b-col>
                    </b-row>
                    <b-row class="p-0 m-0 mt-5">
                        <b-col cols="12" md="3" class="p-0 m-0">
                            <b-form-group
                                id="itemWeightFg"
                                description="in pounds(lb.)"
                                label="Weight"
                                label-for="itemWeight"
                            >
                                <b-input-group append="lb.">
                                    <b-form-input id="itemWeight" v-model="product.local_db.weight || ''" trim disabled/>
                                </b-input-group>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row class="p-0 m-0">
                        <b-col cols="12" md="4" class="p-0">
                            <b-form-group
                                id="itemLengthFg"
                                description="Length"
                                label="Dimensions"
                                label-for="itemLength"
                            >
                                <b-input-group prepend="L">
                                    <b-form-input id="itemLength" v-model="product.local_db.length || ''" trim disabled/>
                                </b-input-group>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="4" class="p-0">
                            <label id="itemWidthFg__BV_label_" for="itemWidth" class="d-block">&nbsp;</label>
                            <div class="bv-no-focus-ring">
                                <div role="group" class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">W</div>
                                    </div>
                                    <input id="itemWidth" type="text"
                                           disabled="disabled" class="form-control"
                                           aria-describedby="widthfg__BV_description_" v-model="product.local_db.width">
                                </div>
                                <small tabindex="-1"
                                       id="itemWidthFg__BV_description_"
                                       class="form-text text-muted">Width</small>
                            </div>
                        </b-col>
                        <b-col cols="12" md="4" class="p-0">
                            <label id="itemHeightfg__BV_label_" for="itemHeight" class="d-block">&nbsp;</label>
                            <div class="bv-no-focus-ring">
                                <div role="group" class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">H</div>
                                    </div>
                                    <input id="itemHeight" type="text"
                                           disabled="disabled" class="form-control"
                                           aria-describedby="widthfg__BV_description_" v-model="product.local_db.height">
                                </div>
                                <small tabindex="-1"
                                       id="itemHeightFg__BV_description_"
                                       class="form-text text-muted">Height</small>
                            </div>
                        </b-col>
                    </b-row>
                </b-col>
                <b-col cols="12" md="6" class="mt-5">
                    <b-row class="p-0 m-0">
                        <b-col cols="12" class="p-0 m-0 pl-1">
                            <h4>Pallet</h4>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12">
                            <b-img-lazy :src="palleteImageUrl(420, 420)" />
                        </b-col>
                    </b-row>
                    <b-row class="p-0 m-0 mt-5">
                        <b-col cols="12" md="6" class="pl-0 pr-sm-0">
                            <b-form-group
                                id="palletPcsPUnitFg"
                                description="per Carton"
                                label="Qunatity"
                                label-for="palletPcsPCt"
                            >
                                <b-input-group append="pcs">
                                    <b-form-input id="itemLength" v-model="product.local_db.pallet.cartonqty || 0" trim disabled/>
                                </b-input-group>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6" class="pr-0 pl-sm-0">
                            <label id="palletPcsPPalletfg__BV_label_" for="palletPcsPPallet" class="d-block">&nbsp;</label>
                            <div class="bv-no-focus-ring">
                                <div role="group" class="input-group">
                                    <input id="palletPcsPPallet" type="text"
                                           disabled="disabled" class="form-control"
                                           aria-describedby="widthfg__BV_description_" v-model="product.local_db.pallet.totalpcs">
                                    <div class="input-group-append">
                                        <div class="input-group-text">pcs</div>
                                    </div>
                                </div>
                                <small tabindex="-1"
                                       id="palletPcsPPallet__BV_description_"
                                       class="form-text text-muted">per Pallet</small>
                            </div>
                        </b-col>
                    </b-row>
                    <b-row class="p-0 m-0">
                        <b-col cols="12" md="4" class="p-0">
                            <b-form-group
                                id="palletLengthFg"
                                description="Length"
                                label="Dimensions"
                                label-for="palletLength"
                            >
                                <b-input-group prepend="L">
                                    <b-form-input id="palletLength" v-model="product.local_db.pallet.length || ''" trim disabled/>
                                </b-input-group>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="4" class="p-0">
                            <label id="palletWidthFg__BV_label_" for="palletWidth" class="d-block">&nbsp;</label>
                            <div class="bv-no-focus-ring">
                                <div role="group" class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">W</div>
                                    </div>
                                    <input id="palletWidth" type="text"
                                           disabled="disabled" class="form-control"
                                           aria-describedby="widthfg__BV_description_" v-model="product.local_db.pallet.width">
                                </div>
                                <small tabindex="-1"
                                       id="palletWidthFg__BV_description_"
                                       class="form-text text-muted">Width</small>
                            </div>
                        </b-col>
                        <b-col cols="12" md="4" class="p-0">
                            <label id="palletHeightfg__BV_label_" for="palletHeight" class="d-block">&nbsp;</label>
                            <div class="bv-no-focus-ring">
                                <div role="group" class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">H</div>
                                    </div>
                                    <input id="palletHeight" type="text"
                                           disabled="disabled" class="form-control"
                                           aria-describedby="widthfg__BV_description_" v-model="product.local_db.pallet.height">
                                </div>
                                <small tabindex="-1"
                                       id="palletHeightFg__BV_description_"
                                       class="form-text text-muted">Height</small>
                            </div>
                        </b-col>
                    </b-row>
                </b-col>
            </b-row>
        </b-card-body>
    </b-card>
</template>
<!--            <CTabs variant="tabs">-->
<!--                <CTab title="Amazon FBA">-->
<!--                    <div class="p-2">FBA</div>-->
<!--                </CTab>-->
<!--                <CTab title="Channel Advisor">-->
<!--                    <div class="p-2">Channel Advisor</div>-->
<!--                </CTab>-->
<!--                <CTab title="Shopify">-->
<!--                    <div class="p-2">Shopify</div>-->
<!--                </CTab>-->
<!--                <CTab title="Tools">-->
<!--                    <div class="p-2">Tools</div>-->
<!--                </CTab>-->
<!--            </CTabs>-->
<!--        </b-card-body>-->
<!--    </b-card>-->
<!--</template>-->

<script>
import api from "../../api";

export default {
    name: "CreateFulFillment",
    props: {
        sku: String,
    },
    data() {
        return {
            product: {
                images: [
                    {
                        src: 'https://via.placeholder.com/800'
                    }
                ]
            },
            active: {
                "yes": {
                    "variant": "success",
                    "text": "Yes"
                },
                "no": {
                    "variant": "danger",
                    "text": "No"
                },
            }
        }
    },
    methods: {
        palleteImageUrl(width, height) {
            return this.product.local_db.pallet.image || `https://via.placeholder.com/${width}x${height}?text=Skar+Audio,+Inc.++Image+Missing`;
        },
        itemImageUrl(width, height) {
            return this.product.local_db.image || `https://via.placeholder.com/${width}x${height}?text=Skar+Audio,+Inc.++Image+Missing`;
        },
        toggleOption() {
            this.product.local_db.active = this.product.local_db.active === 'yes' ? 'no' : 'yes';
        },
        async getProduct() {
            // try {
            //     const response = await api.get('/api/v1/products/' + this.sku);
            //     this.product = response.data;
            // } catch(err) {
            //     console.log(err)
            // }
        }
    },
    mounted() {
        this.getProduct();
    }
}
</script>

<style scoped>

</style>
