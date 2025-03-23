<template>
    <div class="mb-2 flex items-center justify-between">
        <h1 class="text-3xl font-semibold">Dashboard</h1>
        <div class="flex items-center">
            <label class="mr-2">Change Date Period</label>
            <CustomInput
                type="select"
                v-model="chosenDate"
                @change="onDatePickerChange"
                :select-options="dateOptions"
            />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
        <div class="animate-fade-in-down bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
            <label class="text-lg font-semibold block mb-2">Active Customers</label>
            <template v-if="!loading.customersCount">
                <span class="text-3xl font-semibold">{{ customersCount }}</span>
            </template>
            <Spinner v-else text=""/>
        </div>

        <div
            class="animate-fade-in-down bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center"
            style="animation-delay: 0.1s"
        >
            <label class="text-lg font-semibold block mb-2">Active Products</label>
            <template v-if="!loading.productsCount">
                <span class="text-3xl font-semibold">{{ productsCount }}</span>
            </template>
            <Spinner v-else text=""/>
        </div>

        <div
            class="animate-fade-in-down bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center"
            style="animation-delay: 0.2s"
        >
            <label class="text-lg font-semibold block mb-2">Paid Orders</label>
            <template v-if="!loading.paidOrders">
                <span class="text-3xl font-semibold">{{ paidOrders }}</span>
            </template>
            <Spinner v-else text=""/>
        </div>

        <div
            class="animate-fade-in-down bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center"
            style="animation-delay: 0.3s"
        >
            <label class="text-lg font-semibold block mb-2">Total Income</label>
            <template v-if="!loading.totalIncome">
                <span class="text-3xl font-semibold">{{ totalIncome }}</span>
            </template>
            <Spinner v-else text=""/>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div class="bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
            <label class="text-lg font-semibold block mb-2">Orders by Country</label>
            <template v-if="!loading.ordersByCountry">
                <div class="w-[140px] h-[200px] relative">
                    <canvas ref="chartCanvas"></canvas>
                </div>
            </template>
            <Spinner v-else text=""/>
        </div>

        <div class="bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
            <label class="text-lg font-semibold block mb-2">Orders by Day</label>
            <template v-if="!loading.ordersByDay">
                <div class="w-full h-64 relative">
                    <canvas ref="barCanvas"></canvas>
                </div>
            </template>
            <Spinner v-else text=""/>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import axiosClient from '../axios.js'
import Spinner from '../components/core/Spinner.vue'
import CustomInput from '../components/core/CustomInput.vue'

import {
    Chart,
    DoughnutController,
    ArcElement,
    Tooltip,
    Legend,
    BarController,
    BarElement,
    CategoryScale,
    LinearScale
} from 'chart.js'

Chart.register(
    DoughnutController,
    ArcElement,
    Tooltip,
    Legend,
    BarController,
    BarElement,
    CategoryScale,
    LinearScale
)

const dateOptions = ref([
    { key: '1d', text: 'Last Day' },
    { key: '1k', text: 'Last Week' },
    { key: '2k', text: 'Last 2 Weeks' },
    { key: '1m', text: 'Last Month' },
    { key: '3m', text: 'Last 3 Months' },
    { key: '6m', text: 'Last 6 Months' },
    { key: 'all', text: 'All Time' }
])

const chosenDate = ref('all')

const loading = ref({
    customersCount: true,
    productsCount: true,
    paidOrders: true,
    totalIncome: true,
    ordersByCountry: true,
    ordersByDay: true
})

const customersCount = ref(0)
const productsCount = ref(0)
const paidOrders = ref(0)
const totalIncome = ref(0)

const chartCanvas = ref(null)
let chartInstance = null

const barCanvas = ref(null)
let barChartInstance = null

function updatePieChart(countries) {
    const chartData = {
        labels: countries.map(item => item.name),
        datasets: [
            {
                data: countries.map(item => item.count),
                backgroundColor: [
                    '#F8BBD0',
                    '#C5E1A5',
                    '#BBDEFB',
                    '#FFE0B2',
                    '#FFF9C4',
                    '#D7BCD2'
                ]
            }
        ]
    }

    if (chartInstance) {
        chartInstance.data = chartData
        chartInstance.update()
    } else {
        chartInstance = new Chart(chartCanvas.value, {
            type: 'doughnut',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            padding: 16
                        }
                    }
                }
            }
        })
    }
}

function updateBarChart(dailyOrders) {
    const chartData = {
        labels: dailyOrders.map(item => item.date),
        datasets: [
            {
                label: 'Orders',
                data: dailyOrders.map(item => item.count),
                backgroundColor: '#F8BBD0'
            }
        ]
    }

    if (barChartInstance) {
        barChartInstance.data = chartData
        barChartInstance.update()
    } else {
        barChartInstance = new Chart(barCanvas.value, {
            type: 'bar',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            padding: 16
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        })
    }
}


function updateDashboard() {
    const d = chosenDate.value

    loading.value = {
        customersCount: true,
        productsCount: true,
        paidOrders: true,
        totalIncome: true,
        ordersByCountry: true,
        ordersByDay: true
    }

    axiosClient.get('/dashboard/customers-count', {params: {d}})
        .then(({data}) => {
            customersCount.value = data
        })
        .finally(() => {
            loading.value.customersCount = false
        })

    axiosClient.get('/dashboard/products-count', {params: {d}})
        .then(({data}) => {
            productsCount.value = data
        })
        .finally(() => {
            loading.value.productsCount = false
        })

    axiosClient.get('/dashboard/orders-count', {params: {d}})
        .then(({data}) => {
            paidOrders.value = data
        })
        .finally(() => {
            loading.value.paidOrders = false
        })

    axiosClient.get('/dashboard/income-amount', {params: {d}})
        .then(({data}) => {
            totalIncome.value = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'UAH'
            }).format(data)
        })
        .finally(() => {
            loading.value.totalIncome = false
        })

    axiosClient.get('/dashboard/orders-by-country', {params: {d}})
        .then(({data: countries}) => {
            loading.value.ordersByCountry = false
            nextTick(() => {
                updatePieChart(countries)
            })
        })
        .catch(() => {
            loading.value.ordersByCountry = false
        })

    axiosClient.get('/dashboard/orders-by-day', {params: {d}})
        .then(({data: dailyOrders}) => {
            loading.value.ordersByDay = false
            nextTick(() => {
                updateBarChart(dailyOrders)
            })
        })
        .catch(() => {
            loading.value.ordersByDay = false
        })
}

function onDatePickerChange() {
    updateDashboard()
}

onMounted(() => {
    updateDashboard()
})
</script>

<style scoped>
canvas {
    width: 100% !important;
    height: 100% !important;
}
</style>
