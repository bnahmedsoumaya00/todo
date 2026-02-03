<template>
  <div :class="cardClasses">
    <div v-if="title || $slots.header" class="border-b border-gray-200 px-6 py-4">
      <slot name="header">
        <h3 v-if="title" class="text-lg font-semibold text-gray-900">{{ title }}</h3>
        <p v-if="subtitle" class="mt-1 text-sm text-gray-600">{{ subtitle }}</p>
      </slot>
    </div>
    
    <div class="px-6 py-4">
      <slot></slot>
    </div>
    
    <div v-if="$slots.actions" class="border-t border-gray-200 px-6 py-4 bg-gray-50">
      <slot name="actions"></slot>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  title: {
    type: String,
    default: ''
  },
  subtitle: {
    type: String,
    default: ''
  },
  elevation: {
    type: Number,
    default: 2,
    validator: (value) => value >= 0 && value <= 5
  },
  flat: {
    type: Boolean,
    default: false
  }
})

const cardClasses = computed(() => {
  const baseClasses = 'bg-white rounded-lg overflow-hidden'
  
  const shadowClasses = {
    0: '',
    1: 'shadow-sm',
    2: 'shadow',
    3: 'shadow-md',
    4: 'shadow-lg',
    5: 'shadow-xl'
  }
  
  const shadow = props.flat ? '' : shadowClasses[props.elevation]
  
  return [baseClasses, shadow].filter(Boolean).join(' ')
})
</script>