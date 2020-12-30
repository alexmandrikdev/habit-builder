<template>
    <div>
        <div v-if="series[0].data.length" id="chart">
            <div id="chart-timeline">
                <apexchart
                    ref="chart"
                    type="area"
                    height="350"
                    :options="chartOptions"
                    :series="series"
                ></apexchart>
            </div>
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
                    min: new Date('01 Mar 2012').getTime(),
                    tickAmount: 6,
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
            },

            selection: 'one_year',
        };
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

                    res.data.forEach(goal => {
                        const finishedAt = new Date(goal.finished_at);
                        const createdAt = new Date(goal.created_at);

                        this.series[0].data.push([createdAt.getTime(), 0]);

                        this.series[0].data.push([
                            finishedAt.getTime(),
                            Math.floor(
                                (finishedAt - createdAt) /
                                    (1000 * 60 * 60 * 24),
                            ),
                        ]);
                    });
                });
        },
    },
};
</script>

<style scoped></style>
