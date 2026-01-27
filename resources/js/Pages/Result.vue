<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Pie } from 'vue-chartjs';
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';

// Chart.js のコンポーネントを登録
ChartJS.register(ArcElement, Tooltip, Legend);

const props = defineProps({
    result: Object,
    method: String,
    expenses: Number,
    inputData: Object,
});

// グラフ用のデータ
const chartData = computed(() => ({
    labels: ['Aさん', 'Bさん'],
    datasets: [
        {
            data: [props.result.splitA, props.result.splitB],
            backgroundColor: ['#3B82F6', '#EC4899'],
            borderColor: ['#ffffff', '#ffffff'],
            borderWidth: 2,
        }
    ]
}));

// グラフのオプション
const chartOptions = {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
        legend: {
            position: 'bottom',
            labels: {
                font: {
                    size: 14
                },
                padding: 15
            }
        },
        tooltip: {
            callbacks: {
                label: function(context) {
                    return context.label + ': ' + context.parsed + '万円';
                }
            }
        }
    }
};

// アドバイスを生成
const advice = computed(() => {
    if (props.method === 'equal') {
        return '均等に分担で、シンプルで分かりやすいですね！';
    }
    
    if (props.result.burdenRateA && props.result.burdenRateB) {
        const avgRate = (props.result.burdenRateA + props.result.burdenRateB) / 2;
        
        if (avgRate < 25) {
            return 'お互い負担が軽めで、貯金しやすいバランスです！';
        } else if (avgRate < 35) {
            return 'バランスの良い負担割合です！';
        } else {
            return '生活費の割合がやや高めです。節約の余地があるかもしれません。';
        }
    }
    
    return 'この比率で無理なく分担できそうですね！';
});

// バッジの色
const getBadgeClass = computed(() => {
    if (!props.result.burdenRateA) return 'badge-primary';
    
    const avgRate = (props.result.burdenRateA + props.result.burdenRateB) / 2;
    if (avgRate < 25) return 'badge-success';
    if (avgRate < 35) return 'badge-info';
    return 'badge-warning';
});
</script>

<template>
    <Head title="結果 - ふたり家計簿" />
    
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white">
        <div class="max-w-md mx-auto p-6">
            <!-- ヘッダー -->
            <div class="text-center mb-8 mt-8">
                <h1 class="text-2xl font-bold text-blue-600 mb-2">
                    診断結果
                </h1>
            </div>

            <!-- 結果カード -->
            <div class="card bg-base-100 shadow-xl mb-4">
                <div class="card-body">
                    <h2 class="card-title justify-center">
                        おすすめの分担方法
                    </h2>
                    
                    <!-- 分担比率 -->
                    <div class="text-center my-6">
                        <div class="text-sm text-gray-600 mb-2">分担比率</div>
                        <div class="text-5xl font-bold text-primary">
                            {{ result.ratioA }}:{{ result.ratioB }}
                        </div>
                    </div>
                    
                    <!-- 月の生活費 -->
                    <div class="text-center mb-4">
                        <div class="text-sm text-gray-600">月の生活費</div>
                        <div class="text-2xl font-bold">
                            {{ expenses.toLocaleString() }}万円
                        </div>
                    </div>

                    <div class="divider"></div>
                    
                    <!-- グラフ -->
                    <div class="my-6">
                        <div class="text-center text-sm text-gray-600 mb-4">
                            分担割合（円グラフ）
                        </div>
                        <div class="mx-auto" style="max-width: 300px; height: 300px;">
                            <Pie :data="chartData" :options="chartOptions" />
                        </div>
                    </div>

                    <div class="divider"></div>
                    
                    <!-- 各自の負担額 -->
                    <div class="grid grid-cols-2 gap-4 my-4">
                        <!-- Aさん -->
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <div class="text-sm text-gray-600 mb-1">Aさん</div>
                            <div class="text-2xl font-bold text-blue-600">
                                {{ result.splitA.toLocaleString() }}
                            </div>
                            <div class="text-xs text-gray-500">万円/月</div>
                            <div v-if="result.burdenRateA" class="mt-2">
                                <div class="badge badge-sm" :class="getBadgeClass">
                                    手取りの{{ result.burdenRateA }}%
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bさん -->
                        <div class="bg-pink-50 rounded-lg p-4 text-center">
                            <div class="text-sm text-gray-600 mb-1">Bさん</div>
                            <div class="text-2xl font-bold text-pink-600">
                                {{ result.splitB.toLocaleString() }}
                            </div>
                            <div class="text-xs text-gray-500">万円/月</div>
                            <div v-if="result.burdenRateB" class="mt-2">
                                <div class="badge badge-sm" :class="getBadgeClass">
                                    手取りの{{ result.burdenRateB }}%
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>
                    
                    <!-- アドバイス -->
                    <div class="alert alert-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ advice }}</span>
                    </div>
                    
                    <!-- アクションボタン -->
                    <div class="card-actions justify-center mt-6 gap-2">
                        <Link 
                            :href="route('budget.index', inputData)"
                            class="btn btn-outline btn-primary"
                        >
                            もう一度計算
                        </Link>
                        
                        <button 
                            @click="shareResult"
                            class="btn btn-primary"
                        >
                            シェアする
                        </button>
                    </div>
                </div>
            </div>

            <!-- 補足情報 -->
            <div v-if="result.incomeA && result.incomeB" class="text-center text-sm text-gray-600">
                年収：Aさん {{ result.incomeA }}万円 / Bさん {{ result.incomeB }}万円
            </div>

            <!-- リンクコピー完了トースト -->
            <div v-if="showToast" class="toast toast-end toast-bottom">
                <div class="alert alert-success">
                <span>リンクをコピーしました！</span>
                </div>
            </div>
        </div>
    </div>

    <!-- シェアモーダル -->
    <dialog id="share_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">シェアする</h3>
            
            <div class="space-y-3">
                <!-- Twitter/X -->
                <button 
                    @click="shareToTwitter"
                    class="btn btn-outline w-full justify-start gap-3"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                    X (Twitter) でシェア
                </button>
                
                <!-- LINE -->
                <button 
                    @click="shareToLine"
                    class="btn btn-outline w-full justify-start gap-3"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.771.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                    </svg>
                    LINE でシェア
                </button>
                
                <!-- リンクをコピー -->
                <button 
                    @click="copyLink"
                    class="btn btn-outline w-full justify-start gap-3"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    リンクをコピー
                </button>
            </div>
            
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn">閉じる</button>
                </form>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</template>

<script>
export default {
    data() {
        return {
            showToast: false,
        };
    },
    methods: {
        shareResult() {
            document.getElementById('share_modal').showModal();
        },
        
        // 共有用URLを生成するヘルパーメソッド
        generateShareUrl() {
            const params = new URLSearchParams({
                expenses: this.expenses,
                method: this.method,
                ratioA: this.result.ratioA,
                ratioB: this.result.ratioB,
                splitA: this.result.splitA,
                splitB: this.result.splitB,
            });
            return `${window.location.origin}/share?${params.toString()}`;
        },
        
        shareToTwitter() {
            const shareUrl = this.generateShareUrl();
            const text = `私たちの家計バランス診断結果：${this.result.ratioA}:${this.result.ratioB}\n月の生活費 ${this.expenses}万円を分担！`;
            const twitterUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(shareUrl)}`;
            window.open(twitterUrl, '_blank', 'width=550,height=420');
        },
        
        shareToLine() {
            const shareUrl = this.generateShareUrl();
            const text = `私たちの家計バランス診断結果：${this.result.ratioA}:${this.result.ratioB}\n月の生活費 ${this.expenses}万円を分担！\n${shareUrl}`;
            const lineUrl = `https://line.me/R/msg/text/?${encodeURIComponent(text)}`;
            window.open(lineUrl, '_blank');
        },
        copyLink() {
            const shareUrl = this.generateShareUrl();
            navigator.clipboard.writeText(shareUrl).then(() => {
            document.getElementById('share_modal').close();
            this.showToast = true;

            setTimeout(() => {
                this.showToast = false;
            }, 2000);
            });
        },
    }
}
</script>