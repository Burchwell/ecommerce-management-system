<template id="systemError">
    <b-container class="d-flex align-items-center min-vh-100">
        <b-row class="w-100 justify-content-center">
            <b-col md="6">
                <div class="w-100">
                    <div class="clearfix">
                        <h1 class="float-left display-3 mr-4">500</h1>
                        <h4 class="pt-3">Oh No! Seems like something broke on our end.</h4>
                        <br/>
                        <p class="text-muted">There was an error that we couldn't fix. Contact your dev team(a.k.a, the genius who messed this up!)</p>
                        <p class="text-muted">
                            <small>Menawhile,...
                                <b-link to="/">Go back to the Dashboard.</b-link>
                                DO NOT PASS GO, DO NOT COLLECT $200
                            </small>
                        </p>
                    </div>
                </div>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
export default {
    name: 'Page500'
}
</script>

<style scoped lang="scss">
#systemError {
    z-index: 1;

    .input-group-prepend {
        padding: 0 10px;
    }
}
</style>
