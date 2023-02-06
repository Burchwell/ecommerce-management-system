<template>
<div class="loading" v-if="$store.state.loading">
    <div class="loader">
        <div class="part">
            <div class="part">
                <div class="part">
                    <div class="part">
                        <div class="part"></div>
                    </div>
                </div>
            </div>
        </div>
        <h3>LOADING ...</h3>
    </div>

</div>
</template>

<script>
    export default {
        name: "Loading",
        props: {
            index: Number,
            fade: Boolean,
            variant: String,
            title: String,
            message: String
        },
        data() {
            return {
                show: true,
                dismissSecs: 5,
                dismissCountDown: 0
            }
        },
        methods: {
            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown
            },
            closeAlert() {
                this.show = false;
            }
        }
    }
</script>

<style scoped lang="scss">
    .loading {
        z-index: 99999;
        background: rgba(61,72,82,.8);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100vw;
        height: 100vh;
        overflow: hidden;
    }

    h3 {
        margin-top: 25px;
    }

    .loader {
        list-style: none;
        padding: 0;
        margin: 0;
        color: #00d2ff;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -150px;
        margin-left: -150px;
        width: 300px;
        height: 300px;
        text-align: center;
    }
    .loader:after {
        content: "";
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        margin-top: -12px;
        margin-left: -12px;
        width: 320px;
        height: 320px;
        border-radius: 50%;
        border: 2px solid;
        -webkit-animation: rotation-z 2s linear 0s infinite;
        animation: rotation-z 2s linear 0s infinite;
    }
    .loader .part {
        border-radius: 50%;
        display: inline-block;
    }
    .loader > .part {
        box-sizing: border-box;
        width: 100%;
        height: 100%;
        border: 25px solid transparent;
        border-left: 25px solid;
        -webkit-animation: stop 3s cubic-bezier(0.28, 1.9, 1, 0.9), rotation-left 2s linear 0s infinite forwards;
        animation: stop 3s cubic-bezier(0.28, 1.9, 1, 0.9), rotation-left 2s linear 0s infinite forwards;
    }
    .loader > .part > .part {
        box-sizing: border-box;
        width: 100%;
        height: 100%;
        border: 25px solid transparent;
        border-left: 25px solid;
        -webkit-animation: stop 3s cubic-bezier(0.28, 1.9, 1, 0.9), rotation-right 1s linear 0s infinite forwards;
        animation: stop 3s cubic-bezier(0.28, 1.9, 1, 0.9), rotation-right 1s linear 0s infinite forwards;
    }
    .loader > .part > .part > .part {
        box-sizing: border-box;
        width: 100%;
        height: 100%;
        border: 20px solid transparent;
        border-left: 20px solid;
        -webkit-animation: stop 3s cubic-bezier(0.28, 1.9, 1, 0.9), rotation-left 3s linear 0s infinite forwards;
        animation: stop 3s cubic-bezier(0.28, 1.9, 1, 0.9), rotation-left 3s linear 0s infinite forwards;
    }
    .loader > .part > .part > .part > .part {
        box-sizing: border-box;
        width: 100%;
        height: 100%;
        border: 15px solid transparent;
        border-left: 15px solid;
        -webkit-animation: stop 3s cubic-bezier(0.28, 1.9, 1, 0.9), rotation-right 10s linear 0s infinite forwards;
        animation: stop 3s cubic-bezier(0.28, 1.9, 1, 0.9), rotation-right 10s linear 0s infinite forwards;
    }
    .loader > .part > .part > .part > .part > .part {
        box-sizing: border-box;
        width: 100%;
        height: 100%;
        border: 15px solid transparent;
        border-left: 15px solid;
        -webkit-animation: stop 3s cubic-bezier(0.28, 1.9, 1, 0.9), rotation-left 1s linear 0s infinite forwards;
        animation: stop 3s cubic-bezier(0.28, 1.9, 1, 0.9), rotation-left 1s linear 0s infinite forwards;
        border-right: 15px solid;
    }
    @-webkit-keyframes scale {
        0% {
            -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
        }
        50% {
            -webkit-transform: scale(1.5, 1.5);
            transform: scale(1.5, 1.5);
        }
        100% {
            -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
        }
    }
    @keyframes scale {
        0% {
            -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
        }
        50% {
            -webkit-transform: scale(1.5, 1.5);
            transform: scale(1.5, 1.5);
        }
        100% {
            -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
        }
    }
    @-webkit-keyframes stop {
        from {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
        to {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
    }
    @keyframes stop {
        from {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
        to {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
    }
    @-webkit-keyframes rotation-z {
        from {
            -webkit-transform: rotateY(0deg);
            transform: rotateY(0deg);
        }
        to {
            -webkit-transform: rotateY(360deg);
            transform: rotateY(360deg);
        }
    }
    @keyframes rotation-z {
        from {
            -webkit-transform: rotateY(0deg);
            transform: rotateY(0deg);
        }
        to {
            -webkit-transform: rotateY(360deg);
            transform: rotateY(360deg);
        }
    }
    @-webkit-keyframes rotation-right {
        from {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        to {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
    @keyframes rotation-right {
        from {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        to {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
    @-webkit-keyframes rotation-left {
        from {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        to {
            -webkit-transform: rotate(-360deg);
            transform: rotate(-360deg);
        }
    }
    @keyframes rotation-left {
        from {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        to {
            -webkit-transform: rotate(-360deg);
            transform: rotate(-360deg);
        }
    }
</style>
