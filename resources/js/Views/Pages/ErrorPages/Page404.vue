<template id="pageNotFound">
    <b-container class="d-flex align-items-center min-vh-100">
        <b-row class="w-100 justify-content-center">
            <b-col md="6">
                <div class="w-100">
                    <div class="clearfix">
                        <h1 class="float-left display-3 mr-4">404</h1>
                        <h4 class="pt-3">Well now! Seems like you're lost.</h4>
                        <p class="text-muted">Try to follow your Breadcrumb-Trail back to civilization.</p>
                        <p class="text-muted"><small>No Breadcrumb trail?
                            <b-link to="/">Try this link.</b-link>
                            You lucky duck!</small></p>
                    </div>
                </div>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
export default {
    name: 'Page404'
}
</script>

<style scoped lang="scss">
#pageNotFound {
    z-index: 1;

    .input-group-prepend {
        padding: 0 10px;
    }
}
</style>
