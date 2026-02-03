import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import BaseButton from './BaseButton.vue'

describe('BaseButton', () => {
  it('renders correctly with default props', () => {
    const wrapper = mount(BaseButton, {
      slots: {
        default: 'Click me'
      }
    })
    
    expect(wrapper.text()).toContain('Click me')
    expect(wrapper.find('button').exists()).toBe(true)
  })

  it('emits click event when clicked', async () => {
    const wrapper = mount(BaseButton, {
      slots: {
        default: 'Click me'
      }
    })
    
    await wrapper.find('button').trigger('click')
    
    expect(wrapper.emitted('click')).toBeTruthy()
    expect(wrapper.emitted('click')).toHaveLength(1)
  })

  it('shows loading state when loading prop is true', () => {
    const wrapper = mount(BaseButton, {
      props: {
        loading: true
      },
      slots: {
        default: 'Loading'
      }
    })
    
    expect(wrapper.html()).toContain('⏳')
    expect(wrapper.find('button').attributes('disabled')).toBeDefined()
  })

  it('disables button when disabled prop is true', () => {
    const wrapper = mount(BaseButton, {
      props: {
        disabled: true
      },
      slots: {
        default: 'Disabled'
      }
    })
    
    expect(wrapper.find('button').attributes('disabled')).toBeDefined()
    expect(wrapper.find('button').classes()).toContain('cursor-not-allowed')
  })

  it('does not emit click when disabled', async () => {
    const wrapper = mount(BaseButton, {
      props: {
        disabled: true
      },
      slots: {
        default: 'Disabled'
      }
    })
    
    await wrapper.find('button').trigger('click')
    
    expect(wrapper.emitted('click')).toBeFalsy()
  })

  it('applies correct variant classes for primary', () => {
    const wrapper = mount(BaseButton, {
      props: {
        variant: 'primary'
      }
    })
    
    const button = wrapper.find('button')
    expect(button.classes()).toContain('bg-blue-600')
    expect(button.classes()).toContain('text-white')
  })

  it('applies correct variant classes for danger', () => {
    const wrapper = mount(BaseButton, {
      props: {
        variant: 'danger'
      }
    })
    
    const button = wrapper.find('button')
    expect(button.classes()).toContain('bg-red-600')
  })

  it('applies correct size classes', () => {
    const wrapperSmall = mount(BaseButton, {
      props: { size: 'small' }
    })
    const wrapperLarge = mount(BaseButton, {
      props: { size: 'large' }
    })
    
    expect(wrapperSmall.find('button').classes()).toContain('text-sm')
    expect(wrapperLarge.find('button').classes()).toContain('text-lg')
  })

  it('renders icon when provided', () => {
    const wrapper = mount(BaseButton, {
      props: {
        icon: '🔥'
      },
      slots: {
        default: 'With Icon'
      }
    })
    
    expect(wrapper.text()).toContain('🔥')
  })

  it('hides icon when loading', () => {
    const wrapper = mount(BaseButton, {
      props: {
        icon: '🔥',
        loading: true
      }
    })
    
    expect(wrapper.text()).not.toContain('🔥')
    expect(wrapper.text()).toContain('⏳')
  })
})