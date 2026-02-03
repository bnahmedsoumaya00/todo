<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="modelValue" class="fixed inset-0 z-50 overflow-y-auto" @click="handleBackdropClick">
        <div class="flex min-h-screen items-center justify-center p-4">
          <!-- Backdrop -->
          <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
          
          <!-- Modal -->
          <div
            :class="modalClasses"
            :style="{ maxWidth: maxWidth }"
            @click.stop
          >
            <!-- Header -->
            <div v-if="title || $slots.header" class="border-b border-gray-200 px-6 py-4">
              <slot name="header">
                <div class="flex items-center justify-between">
                  <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
                  <button
                    type="button"
                    class="text-gray-400 hover:text-gray-600 transition-colors"
                    @click="closeModal"
                  >
                    <span class="text-2xl">&times;</span>
                  </button>
                </div>
              </slot>
            </div>
            
            <!-- Content -->
            <div class="px-6 py-4">
              <slot></slot>
            </div>
            
            <!-- Actions -->
            <div v-if="$slots.actions" class="border-t border-gray-200 px-6 py-4 bg-gray-50">
              <slot name="actions"></slot>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { computed } from 'vue'
const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: ''
  },
  maxWidth: {
    type: String,
    default: '600px'
  },
  persistent: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue', 'close'])

const modalClasses = computed(() => {
  return 'relative bg-white rounded-lg shadow-xl w-full transform transition-all'
})

const closeModal = () => {
  emit('update:modelValue', false)
  emit('close')
}

const handleBackdropClick = () => {
  if (!props.persistent) {
    closeModal()
  }
}
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
</style>