<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

// 前回の入力値を受け取る
const props = defineProps({
    previousInput: Object,
});

// フォームの状態管理（前回の値で初期化）
const form = useForm({
    expenses: props.previousInput?.expenses || null,
    splitMethod: props.previousInput?.splitMethod || 'income',
    incomeA: props.previousInput?.incomeA || null,
    incomeB: props.previousInput?.incomeB || null,
    ratioA: props.previousInput?.ratioA || 60,
    ratioB: props.previousInput?.ratioB || 40,
});

// 詳細入力モード
const isDetailMode = ref(false);

// 内訳入力
const details = ref({
    rent: null,
    utilities: null,
    food: null,
    communication: null,
    other: null,
});

// 内訳から合計を自動計算
const calculatedTotal = computed(() => {
    return Object.values(details.value)
        .reduce((sum, val) => sum + (parseFloat(val) || 0), 0);
});

// 詳細モードの時は自動計算した合計を使う
watch(calculatedTotal, (newVal) => {
    if (isDetailMode.value && newVal > 0) {
        form.expenses = newVal;
    }
});

// ratioA が変わったら ratioB を自動計算
watch(() => form.ratioA, (newVal) => {
    form.ratioB = 100 - newVal;
});

// 詳細モードの切り替え
const toggleDetail = () => {
    isDetailMode.value = !isDetailMode.value;
    if (!isDetailMode.value) {
        // ざっくりモードに戻す時は内訳をクリア
        details.value = {
            rent: null,
            utilities: null,
            food: null,
            communication: null,
            other: null,
        };
    }
};

// フォーム送信
const submit = () => {
    form.post('/calculate');
};
</script>

<template>
    <Head title="入力 - ふたり家計簿" />
    
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white">
        <div class="max-w-md mx-auto p-6">
            <!-- ヘッダー -->
            <div class="text-center mb-8 mt-8">
                <h1 class="text-3xl font-bold text-blue-600 mb-2 font-display">
                    ふたり家計簿
                </h1>
                <p class="text-sm text-gray-600">
                    30秒で分かる、理想の分担方法
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- Step 1: 生活費入力 -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-lg">月の生活費</h2>
                        
                        <!-- 合計額入力 -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium">
                                    月の生活費合計
                                </span>
                            </label>
                            <div class="relative">
                            <input 
                                v-model="form.expenses"
                                :disabled="isDetailMode"
                                type="number"
                                step="0.1"
                                min="0.1"
                                class="input input-bordered w-full text-xl font-bold"
                                :class="{ 
                                    'bg-gray-100': isDetailMode,
                                    'input-error': form.errors.expenses 
                                }"
                                placeholder="30"
                                required
                            />
                            <label v-if="form.errors.expenses" class="label">
                                <span class="label-text-alt text-error">{{ form.errors.expenses }}</span>
                            </label>
                                <span class="absolute right-4 top-3 text-gray-500">
                                    万円
                                </span>
                            </div>
                            <label v-if="isDetailMode" class="label">
                                <span class="label-text-alt text-info">
                                    ※ 内訳から自動計算されます
                                </span>
                            </label>
                        </div>

                        <!-- 詳細入力アコーディオン -->
                        <div class="mt-4">
                            <button 
                                type="button"
                                @click="toggleDetail"
                                class="btn btn-ghost btn-sm w-full justify-between"
                            >
                                <span>
                                    {{ isDetailMode ? '▲' : '▼' }}
                                    内訳を入力する（任意）
                                </span>
                                <span v-if="!isDetailMode" class="badge badge-primary badge-sm">
                                    より詳しく
                                </span>
                            </button>
                            
                            <!-- 詳細入力欄 -->
                            <div v-if="isDetailMode" class="mt-4 bg-base-200 rounded-lg p-4">
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="form-control">
                                        <label class="label py-1">
                                            <span class="label-text text-sm">家賃</span>
                                        </label>
                                        <input 
                                            v-model="details.rent"
                                            type="number"
                                            step="0.1"
                                            class="input input-bordered input-sm"
                                            placeholder="12"
                                        />
                                    </div>
                                    
                                    <div class="form-control">
                                        <label class="label py-1">
                                            <span class="label-text text-sm">光熱費</span>
                                        </label>
                                        <input 
                                            v-model="details.utilities"
                                            type="number"
                                            step="0.1"
                                            class="input input-bordered input-sm"
                                            placeholder="1.5"
                                        />
                                    </div>
                                    
                                    <div class="form-control">
                                        <label class="label py-1">
                                            <span class="label-text text-sm">食費</span>
                                        </label>
                                        <input 
                                            v-model="details.food"
                                            type="number"
                                            step="0.1"
                                            class="input input-bordered input-sm"
                                            placeholder="8"
                                        />
                                    </div>
                                    
                                    <div class="form-control">
                                        <label class="label py-1">
                                            <span class="label-text text-sm">通信費</span>
                                        </label>
                                        <input 
                                            v-model="details.communication"
                                            type="number"
                                            step="0.1"
                                            class="input input-bordered input-sm"
                                            placeholder="1.5"
                                        />
                                    </div>
                                    
                                    <div class="form-control col-span-2">
                                        <label class="label py-1">
                                            <span class="label-text text-sm">その他</span>
                                        </label>
                                        <input 
                                            v-model="details.other"
                                            type="number"
                                            step="0.1"
                                            class="input input-bordered input-sm"
                                            placeholder="7"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: 分担方法選択 -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-lg">分担方法</h2>
                        
                        <div class="space-y-2">
                            <!-- 収入比例 -->
                            <label class="label cursor-pointer justify-start gap-4 border rounded-lg p-3 hover:bg-base-200 w-full">
                                <input 
                                    type="radio" 
                                    v-model="form.splitMethod"
                                    value="income"
                                    class="radio radio-primary flex-shrink-0" 
                                />
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium">収入に応じて分担</div>
                                    <div class="text-sm text-gray-500">
                                        年収の比率で公平に分ける
                                    </div>
                                </div>
                            </label>
                            
                            <!-- 均等 -->
                            <label class="label cursor-pointer justify-start gap-4 border rounded-lg p-3 hover:bg-base-200 w-full">
                                <input 
                                    type="radio" 
                                    v-model="form.splitMethod"
                                    value="equal"
                                    class="radio radio-primary flex-shrink-0" 
                                />
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium">均等に分担（50:50）</div>
                                    <div class="text-sm text-gray-500">
                                        シンプルに半分ずつ
                                    </div>
                                </div>
                            </label>
                            
                            <!-- カスタム -->
                            <label class="label cursor-pointer justify-start gap-4 border rounded-lg p-3 hover:bg-base-200 w-full">
                                <input 
                                    type="radio" 
                                    v-model="form.splitMethod"
                                    value="custom"
                                    class="radio radio-primary flex-shrink-0" 
                                />
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium">カスタム（割合を指定）</div>
                                    <div class="text-sm text-gray-500">
                                        自分で好きな比率を設定
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Step 3: 追加入力（分担方法に応じて表示） -->
                
                <!-- 収入比例の場合：年収入力 -->
                <div v-if="form.splitMethod === 'income'" class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-lg">年収を入力</h2>
                        
                        <div class="space-y-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-medium">Aさんの年収</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        v-model="form.incomeA"
                                        type="number"
                                        min="1"
                                        class="input input-bordered w-full"
                                        :class="{ 'input-error': form.errors.incomeA }"
                                        placeholder="400"
                                        required
                                    />
                                    <label v-if="form.errors.incomeA" class="label">
                                        <span class="label-text-alt text-error">{{ form.errors.incomeA }}</span>
                                    </label>
                                    <span class="absolute right-4 top-3 text-gray-500">
                                        万円/年
                                    </span>
                                </div>
                            </div>
                            
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-medium">Bさんの年収</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        v-model="form.incomeB"
                                        type="number"
                                        min="1"
                                        class="input input-bordered w-full"
                                        :class="{ 'input-error': form.errors.incomeB }"
                                        placeholder="300"
                                        required
                                    />
                                    <label v-if="form.errors.incomeB" class="label">
                                        <span class="label-text-alt text-error">{{ form.errors.incomeB }}</span>
                                    </label>
                                    <span class="absolute right-4 top-3 text-gray-500">
                                        万円/年
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- カスタムの場合：比率入力 -->
                <div v-if="form.splitMethod === 'custom'" class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-lg">分担比率を設定</h2>
                        
                        <div class="form-control">
                           
                            <div class="flex items-center gap-3">
                                <div class="flex-1">
                                    <label class="label">
                                        <span class="label-text font-medium">Aさんの比率</span>
                                    </label>
                                    <input 
                                        v-model.number="form.ratioA"
                                        type="number"
                                        min="0"
                                        max="100"
                                        class="input input-bordered w-full text-center"
                                        :class="{ 'input-error': form.errors.ratioA }"
                                    />
                                    <label v-if="form.errors.ratioA" class="label">
                                        <span class="label-text-alt text-error">{{ form.errors.ratioA }}</span>
                                    </label>
                                </div>
                                
                                <span class="text-2xl font-bold">:</span>
                                
                                <div class="flex-1">
                                    <label class="label">
                                        <span class="label-text font-medium">Bさんの比率</span>
                                    </label>
                                    <input 
                                        v-model.number="form.ratioB"
                                        type="number"
                                        min="0"
                                        max="100"
                                        class="input input-bordered w-full text-center"
                                        disabled
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 送信ボタン -->
            <button 
                type="submit"
                :disabled="form.processing"
                class="btn btn-primary btn-lg w-full"
            >
                <span v-if="form.processing" class="loading loading-spinner loading-sm"></span>
                {{ form.processing ? '計算中...' : '結果を見る' }}
            </button>
            </form>
        </div>
    </div>
</template>