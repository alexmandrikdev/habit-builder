<template>
    <div>
        <div v-if="series[0].data.length">
            <div class="d-flex justify-content-center">
                <b-form-select
                    v-model="year"
                    :options="yearOptions"
                    style="max-width: 200px"
                    class="mx-1"
                    @change="zoom"
                />
                <b-form-select
                    v-model="month"
                    :options="monthOptions"
                    style="max-width: 200px"
                    class="mx-1"
                    @change="zoom"
                />
            </div>

            <apexchart
                ref="chart"
                type="area"
                height="350"
                :options="chartOptions"
                :series="series"
            />
        </div>
        <div v-else class="text-center mt-5">
            <b-spinner label="Loading..." class="mx-auto"></b-spinner>
        </div>
    </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts';

export default {
    components: {
        apexchart: VueApexCharts,
    },
    data() {
        return {
            series: [
                {
                    name: 'Goal',
                    data: [],
                },
                {
                    name: 'State',
                    data: [],
                },
            ],
            chartOptions: {
                chart: {
                    id: 'area-datetime',
                    type: 'area',
                    height: 350,
                    zoom: {
                        autoScaleYaxis: true,
                    },
                },
                markers: {
                    size: 0,
                    style: 'hollow',
                },
                xaxis: {
                    type: 'datetime',
                },
                tooltip: {
                    x: {
                        format: 'dd MMM yyyy',
                    },
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 100],
                    },
                },
                colors: ['#C82333', '#008FFB'],
            },
            year: null,
            month: null,
            minYear: null,
            maxYear: null,
            all: {
                min: null,
                max: null,
            },
            monthOptions: [
                { value: null, text: 'Month' },
                { value: 1, text: 'January' },
                { value: 2, text: 'February' },
                { value: 3, text: 'March' },
                { value: 4, text: 'April' },
                { value: 5, text: 'May' },
                { value: 6, text: 'June' },
                { value: 7, text: 'July' },
                { value: 8, text: 'August' },
                { value: 9, text: 'September' },
                { value: 10, text: 'October' },
                { value: 11, text: 'November' },
                { value: 12, text: 'December' },
            ],
        };
    },
    computed: {
        yearOptions() {
            let years = [{ value: null, text: 'Years' }];

            for (let year = this.minYear; year <= this.maxYear; year++) {
                years.push(year);
            }

            return years;
        },
    },
    created() {
        this.fetchData();
    },
    methods: {
        fetchData() {
            axios
                .get('/goals', {
                    params: { habit_id: this.$route.params.id },
                })
                .then(res => {
                    this.chartOptions.xaxis.min = new Date(
                        res.data[0].created_at,
                    ).getTime();

                    this.all.min = new Date(res.data[0].created_at);
                    this.minYear = this.all.min.getFullYear();

                    this.all.max = res.data[res.data.length - 1].finished_at
                        ? new Date(res.data[res.data.length - 1].finished_at)
                        : new Date(res.data[res.data.length - 1].created_at);
                    this.maxYear = this.all.max.getFullYear();

                    res.data.forEach(goal => {
                        const finishedAt = goal.finished_at
                            ? new Date(goal.finished_at)
                            : null;

                        const createdAt = new Date(goal.created_at);

                        this.series[1].data.push([createdAt.getTime(), 0]);

                        this.series[0].data.push([createdAt.getTime() - 1, 0]);

                        this.series[0].data.push([
                            createdAt.getTime(),
                            goal.goal,
                        ]);

                        if (finishedAt) {
                            this.series[1].data.push([
                                finishedAt.getTime(),
                                Math.floor(
                                    (finishedAt - createdAt) /
                                        (1000 * 60 * 60 * 24),
                                ),
                            ]);
                            this.series[0].data.push([
                                finishedAt.getTime(),
                                goal.goal,
                            ]);

                            this.series[1].data.push([
                                finishedAt.getTime() + 1,
                                0,
                            ]);

                            this.series[0].data.push([
                                finishedAt.getTime() + 1,
                                0,
                            ]);
                        }
                    });
                });
        },
        zoom() {
            if (this.year === null) {
                this.$refs.chart.zoomX(this.all.min, this.all.max);
                return;
            }

            if (this.month === null) {
                this.$refs.chart.zoomX(
                    new Date(`01 Jan ${this.year}`).getTime(),
                    new Date(`31 Dec ${this.year}`).getTime(),
                );
                return;
            } else {
                this.$refs.chart.zoomX(
                    new Date(`${this.month} 01 ${this.year}`).getTime(),
                    new Date(this.year, this.month, 0).getTime(),
                );
                return;
            }
        },
    },
};
</script>

<style scoped></style>
