import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import TodoForm from '../TodoForm.vue'

describe('TodoForm', () => {
  it('renders an input and a button', () => {
    // 'mount' renders the component into a virtual DOM.
    const wrapper = mount(TodoForm);

    // Assert that an input element exists.
    expect(wrapper.find('input[type="text"]').exists()).toBe(true);

    // Assert that a button element exists.
    expect(wrapper.find('button[type="submit"]').exists()).toBe(true);
  });
});
