import { describe, it, expect } from 'vitest'

function soma(a, b) {
  return a + b
}

describe('soma', () => {
  it('soma dois números', () => {
    expect(soma(2, 3)).toBe(5)
  })
})