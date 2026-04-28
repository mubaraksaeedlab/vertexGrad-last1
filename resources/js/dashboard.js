// resources/js/dashboard.js
document.addEventListener('DOMContentLoaded', function() {
    // التحقق من وجود ApexCharts
    if (typeof ApexCharts === 'undefined') {
        console.error('ApexCharts is not loaded. Please include ApexCharts library.');
        return;
    }

    // التحقق من وجود البيانات
    if (!window.dashboardData) {
        console.error('Dashboard data not found. Make sure window.dashboardData is set.');
        return;
    }

    const { stats, submittedByMonth, activeByMonth, rejectedByMonth, completedByMonth, months } = window.dashboardData;

    // دالة مساعدة لإنشاء المخططات بأمان
    const safeRender = (elementId, chartConfig) => {
        const element = document.querySelector(elementId);
        if (element) {
            try {
                const chart = new ApexCharts(element, chartConfig);
                chart.render();
                return chart;
            } catch (error) {
                console.error(`Error rendering chart ${elementId}:`, error);
            }
        }
        return null;
    };

    // Main Pipeline Chart
    safeRender("#activities-chart", {
        series: [
            { name: 'مقدم', data: submittedByMonth || [] },
            { name: 'نشط', data: activeByMonth || [] },
            { name: 'مرفوض', data: rejectedByMonth || [] },
            { name: 'مكتمل', data: completedByMonth || [] }
        ],
        chart: {
            type: 'area',
            height: 350,
            toolbar: { show: false },
            zoom: { enabled: false },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        colors: ['#6366f1', '#10b981', '#ef4444', '#f59e0b'],
        dataLabels: { enabled: false },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3
            }
        },
        grid: {
            borderColor: '#e2e8f0',
            strokeDashArray: 4
        },
        xaxis: {
            categories: months || [],
            labels: { style: { colors: '#64748b' } }
        },
        yaxis: {
            labels: { style: { colors: '#64748b' } }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            markers: { radius: 6 }
        },
        tooltip: {
            theme: 'light',
            x: { format: 'dd MMM yyyy' }
        }
    });

    // Status Donut Chart
    safeRender("#project-status-chart", {
        series: [
            stats?.pending_projects || 0,
            stats?.active_projects || 0,
            stats?.completed_projects || 0,
            stats?.rejected_projects || 0
        ],
        chart: {
            type: 'donut',
            height: 280,
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        labels: ['قيد الانتظار', 'نشط', 'مكتمل', 'مرفوض'],
        colors: ['#f59e0b', '#10b981', '#6366f1', '#ef4444'],
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            fontSize: '13px',
            markers: { radius: 6 }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'الإجمالي',
                            fontSize: '14px',
                            fontWeight: 600,
                            color: '#0f172a'
                        }
                    }
                }
            }
        },
        dataLabels: { enabled: false },
        stroke: { show: false },
        tooltip: {
            theme: 'light',
            y: {
                formatter: function(value) {
                    return value + ' مشروع';
                }
            }
        }
    });

    // Side Stats Radial Charts
    const createRadialChart = (elementId, value, color) => {
        if (value > 0) {
            safeRender(elementId, {
                series: [value],
                chart: { 
                    type: 'radialBar', 
                    height: 80, 
                    sparkline: { enabled: true } 
                },
                plotOptions: {
                    radialBar: {
                        hollow: { size: '40%' },
                        track: { background: `${color}20` },
                        dataLabels: { show: false }
                    }
                },
                colors: [color]
            });
        }
    };

    if (stats) {
        createRadialChart('#pending-chart', stats.pending_projects || 1, '#f59e0b');
        createRadialChart('#active-chart', stats.active_projects || 1, '#10b981');
        createRadialChart('#rejected-chart', stats.rejected_projects || 1, '#ef4444');
    }

    // Mini sparklines for KPI cards
    const createSparkline = (elementId, data, color) => {
        if (data && data.length > 0) {
            safeRender(elementId, {
                series: [{ data: data }],
                chart: {
                    type: 'line',
                    height: 50,
                    sparkline: { enabled: true }
                },
                colors: [color],
                stroke: { curve: 'smooth', width: 2 },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.3,
                        opacityTo: 0
                    }
                }
            });
        }
    };

    // Create sparklines for KPI cards
    if (stats) {
        const totalProjects = stats.total_projects || 10;
        const students = stats.students || 10;
        const investors = stats.investors || 5;
        const totalFunding = stats.total_funding || 10000;

        createSparkline('#totalProjectsChart', Array(7).fill(totalProjects / 7), '#6366f1');
        createSparkline('#studentsChart', Array(7).fill(students / 7), '#10b981');
        createSparkline('#investorsChart', Array(7).fill(investors / 7), '#f59e0b');
        createSparkline('#fundingChart', Array(7).fill(totalFunding / 7), '#ef4444');
    }
});