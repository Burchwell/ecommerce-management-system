<template>
    <div class="clock-container">
        <div class="clock-col">
            <p class="clock-day clock-timer">
                {{time}}
            </p>
            <p class="clock-label">
                {{date}}
            </p>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    export default {
        name: "Clock",
        data() {
            return {
                showSeconds: true,
                date: '',
                time: ''
            }
        },
        methods: {
            setTime() {
                var _self = this;
                this.date = moment().format('dd[,] LL');
                this.time = moment().format('LTS');
                setInterval(_self.setTime, 1000)
            }
        },
        created() {
            this.setTime();
        }
    }
</script>

<style scoped lang="scss">
    .clock {
        &-container {
            margin: 0 12px 0 0;
        }
        &-timer {
            margin: 0;
            text-align: right;
            &:before {
                color: #fff;
                font-size: 4.2rem;
                text-transform: uppercase;
            }
        }
        &-label {
            color: rgba(0,0,0);
            text-transform: uppercase;
            font-size: .5rem;
            text-align: right;
            margin: 0;
        }
    }

    .c-dark-theme .clock {
        &-container {
            margin: 0 12px 0 0;
        }
        &-timer {
            margin: 0;
            text-align: right;
            color: #fff;
            &:before {
                color: #fff;
                font-size: 4.2rem;
                text-transform: uppercase;
            }
        }
        &-label {
            color: #efefef;
            text-transform: uppercase;
            font-size: .5rem;
            text-align: right;
            margin: 0;
        }
    }
</style>
