<script setup>
    import {ref} from 'vue'
    import { useBase } from '@/lib/base';
    import VueApexCharts from "vue3-apexcharts";
    const apexchart = VueApexCharts
    const { getImage, formatDate } = useBase();

    const barChart = {
        series: [
            {
                name: "Population",
                data: [400, 430, 448, 470, 540, 580, 690, 1100, 1200, 1380]
            }
        ],
        chartOptions: {
            chart: { type: "bar", height: 250, foreColor: "#fff" },
            plotOptions: { bar: { horizontal: true } },
            dataLabels: { enabled: false },
            xaxis: {
                categories: [
                    "South Korea",
                    "Canada",
                    "United Kingdom",
                    "Netherlands",
                    "Italy",
                    "France",
                    "Japan",
                    "United States",
                    "China",
                    "Germany"
                ]
            }
        }
    };
    const barChart2 = {
        series: [
            {
                name: "Population",
                data: [400, 430, 448, 470, 540, 580, 690, 1100, 1200, 1380]
            }
        ],
        chartOptions: {
            chart: { type: "bar", height: 250, foreColor: "#fff" },
            plotOptions: { bar: { horizontal: false } },
            dataLabels: { enabled: false },
            xaxis: {
                categories: [
                    "South Korea",
                    "Canada",
                    "United Kingdom",
                    "Netherlands",
                    "Italy",
                    "France",
                    "Japan",
                    "United States",
                    "China",
                    "Germany"
                ]
            }
        }
    };
    const barChart3 = {
        series: [
            {
                name: "Population",
                data: [400, 430, 448, 470, 540, 580, 690, 1100, 1200, 1380]
            }
        ],
        chartOptions: {
            chart: {type: "bar", height: 250, foreColor: "#fff"},
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '50%',
                    endingShape: 'rounded'
                },
                chart: {
                    type: 'bar',
                    stacked: true,   // 👈 stacked bars
                    height: 350
                },
            },
            dataLabels: {enabled: false},
            xaxis: {
                categories: [
                    "South Korea",
                    "Canada",
                    "United Kingdom",
                    "Netherlands",
                    "Italy",
                    "France",
                    "Japan",
                    "United States",
                    "China",
                    "Germany"
                ]
            }
        }
    };

    // 🔹 Donut chart expects [number, number, ...]
    const donutChart = {
        series: [44, 55, 41, 17, 15],
        chartOptions: {
            chart: { type: "donut",height: 200, foreColor: "#fff" },
            labels: ["Apples", "Bananas", "Oranges", "Grapes", "Berries"],
            responsive: [
                {
                    breakpoint: 480,
                    options: {
                        chart: { width: 200 },
                        legend: { position: "bottom" }
                    }
                }
            ]
        }
    };
    const rediaBar = {
        series: [76, 67, 61, 90],
            chartOptions: {
            chart: {
                height: 300,
                    type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    offsetY: 0,
                        startAngle: 0,
                        endAngle: 270,
                        hollow: {
                        margin: 5,
                            size: '30%',
                            background: 'transparent',
                            image: undefined,
                    },
                    dataLabels: {
                        name: {
                            show: false,
                        },
                        value: {
                            show: false,
                        }
                    },
                    barLabels: {
                        enabled: true,
                            useSeriesColors: true,
                            offsetX: -8,
                            fontSize: '16px',
                            formatter: function(seriesName, opts) {
                            return seriesName + ":  " + opts.w.globals.series[opts.seriesIndex]
                        },
                    },
                }
            },
            colors: ['#1ab7ea', '#0084ff', '#39539E', '#0077B5'],
                labels: ['Vimeo', 'Messenger', 'Facebook', 'LinkedIn'],
                responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        show: false
                    }
                }
            }]
        },
    };
</script>
<template>
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0">Revenue</p>
                                            <h4 class="my-1">$4805</h4>
                                            <p class="mb-0 font-13"><i class='bx bxs-up-arrow align-middle'></i>$34 Since last week</p>
                                        </div>
                                        <div class="widgets-icons ms-auto"><i class='bx bxs-wallet'></i>
                                        </div>
                                    </div>
                                    <div id="chart1"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0">Revenue</p>
                                            <h4 class="my-1">$4805</h4>
                                            <p class="mb-0 font-13"><i class='bx bxs-up-arrow align-middle'></i>$34 Since last week</p>
                                        </div>
                                        <div class="widgets-icons ms-auto"><i class='bx bxs-wallet'></i>
                                        </div>
                                    </div>
                                    <div id="chart1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0">Revenue</p>
                                            <h4 class="my-1">$4805</h4>
                                            <p class="mb-0 font-13"><i class='bx bxs-up-arrow align-middle'></i>$34 Since last week</p>
                                        </div>
                                        <div class="widgets-icons ms-auto"><i class='bx bxs-wallet'></i>
                                        </div>
                                    </div>
                                    <div id="chart1"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0">Revenue</p>
                                            <h4 class="my-1">$4805</h4>
                                            <p class="mb-0 font-13"><i class='bx bxs-up-arrow align-middle'></i>$34 Since last week</p>
                                        </div>
                                        <div class="widgets-icons ms-auto"><i class='bx bxs-wallet'></i>
                                        </div>
                                    </div>
                                    <div id="chart1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0">Revenue</p>
                                            <h4 class="my-1">$4805</h4>
                                            <p class="mb-0 font-13"><i class='bx bxs-up-arrow align-middle'></i>$34 Since last week</p>
                                        </div>
                                        <div class="widgets-icons ms-auto"><i class='bx bxs-wallet'></i>
                                        </div>
                                    </div>
                                    <div id="chart1"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0">Revenue</p>
                                            <h4 class="my-1">$4805</h4>
                                            <p class="mb-0 font-13"><i class='bx bxs-up-arrow align-middle'></i>$34 Since last week</p>
                                        </div>
                                        <div class="widgets-icons ms-auto"><i class='bx bxs-wallet'></i>
                                        </div>
                                    </div>
                                    <div id="chart1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-body">
                                    <apexchart :options="rediaBar.chartOptions" :series="rediaBar.series"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-body">
                                    <apexchart :options="barChart3.chartOptions" :series="barChart3.series"/>
<!--                                    <apexchart :options="donutChart.chartOptions" :series="donutChart.series"/>-->
<!--                                    <apexchart :options="barChart2.chartOptions" :series="barChart2.series"/>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <apexchart :options="barChart.chartOptions" :series="barChart.series"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <apexchart :options="barChart3.chartOptions" :series="barChart3.series"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <apexchart :options="donutChart.chartOptions" :series="donutChart.series"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
