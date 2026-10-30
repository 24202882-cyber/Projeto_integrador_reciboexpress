import { describe, it, expect } from 'vitest';
import { somar } from './math.js';

describe('Função somar', () => {
  it('deve somar dois números corretamente', () => {
    expect(somar(2, 3)).toBe(5);
  });
});