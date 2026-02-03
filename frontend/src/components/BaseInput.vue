<template>
  <div class="w-full">
    <label v-if="label" :for="inputId" class="block text-sm font-medium text-gray-700 mb-1">
      {{ label }}
    </label>
    
    <input
      :id="inputId"
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :disabled="disabled"
      :class="inputClasses"
      @input="handleInput"
      @blur="handleBlur"
    />
    
    <p v-if="errorMessage" class="mt-1 text-sm text-red-600">
      {{ errorMessage }}
    </p>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  modelValue: {
    type: [String, Number],
    default: ''
  },
  label: {
    type: String,
    default: ''
  },
  type: {
    type: String,
    default: 'text'
  },
  placeholder: {
    type: String,
    default: ''
  },
  error: {
    type: String,
    default: ''
  },
  disabled: {
    type: Boolean,
    default: false
  },
  rules: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['update:modelValue', 'blur'])

const inputId = `input-${Math.random().toString(36).substr(2, 9)}`
const internalError = ref('')

const errorMessage = computed(() => props.error || internalError.value)

const inputClasses = computed(() => {
  const baseClasses = 'w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 transition-colors'
  const normalClasses = 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'
  const errorClasses = 'border-red-300 focus:ring-red-500 focus:border-red-500'
  const disabledClasses = 'bg-gray-100 cursor-not-allowed opacity-60'
  
  const stateClasses = errorMessage.value ? errorClasses : normalClasses
  const disabledState = props.disabled ? disabledClasses : ''
  
  return [baseClasses, stateClasses, disabledState].filter(Boolean).join(' ')
})

const handleInput = (event) => {
  const value = event.target.value
  emit('update:modelValue', value)
  validateInput(value)
}

const handleBlur = (event) => {
  validateInput(event.target.value)
  emit('blur', event)
}

const validateInput = (value) => {
  internalError.value = ''
  
  for (const rule of props.rules) {
    const result = rule(value)
    if (result !== true) {
      internalError.value = result
      break
    }
  }
}
</script>