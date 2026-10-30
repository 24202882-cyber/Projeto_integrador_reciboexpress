import { describe, it, expect, beforeEach, vi } from 'vitest';

// 1. Função de busca na API do ViaCEP
async function buscarCep(cep) {
  const cleanCep = cep.replace(/\D/g, '');
  
  if (cleanCep.length !== 8) {
    throw new Error('CEP inválido');
  }

  const response = await fetch(`https://viacep.com.br/ws/${cleanCep}/json/`);
  const data = await response.json();

  if (data.erro) {
    throw new Error('CEP não encontrado');
  }

  return {
    logradouro: data.logradouro,
    bairro: data.bairro,
    localidade: data.localidade,
    uf: data.uf,
  };
}

// 2. Função simulada para preenchimento automático do formulário (Autofill)
function preencherFormularioComCep(dadosEndereco, inputsFormulario) {
  inputsFormulario.logradouro.value = dadosEndereco.logradouro || '';
  inputsFormulario.bairro.value = dadosEndereco.bairro || '';
  inputsFormulario.cidade.value = dadosEndereco.localidade || '';
  inputsFormulario.uf.value = dadosEndereco.uf || '';
}

// 3. Suíte de Testes Automatizados no Vitest
describe('viacep.autofill.spec - busca de CEP', () => {
  beforeEach(() => {
    vi.restoreAllMocks();
  });

  it('deve buscar os dados do CEP com sucesso e preencher o formulário', async () => {
    // Dados simulados de resposta da API
    const mockViaCepResponse = {
      cep: '01001-000',
      logradouro: 'Praça da Sé',
      bairro: 'Sé',
      localidade: 'São Paulo',
      uf: 'SP',
      erro: false
    };

    // Mock da chamada do fetch global
    global.fetch = vi.fn().mockResolvedValue({
      ok: true,
      json: vi.fn().mockResolvedValue(mockViaCepResponse),
    });

    // Mock dos elementos de entrada do formulário
    const mockFormInputs = {
      logradouro: { value: '' },
      bairro: { value: '' },
      cidade: { value: '' },
      uf: { value: '' }
    };

    // Executa a busca
    const endereco = await buscarCep('01001-000');
    
    // Executa o preenchimento automático
    preencherFormularioComCep(endereco, mockFormInputs);

    // Assertions (Verificações)
    expect(global.fetch).toHaveBeenCalledWith('https://viacep.com.br/ws/01001000/json/');
    expect(mockFormInputs.logradouro.value).toBe('Praça da Sé');
    expect(mockFormInputs.bairro.value).toBe('Sé');
    expect(mockFormInputs.cidade.value).toBe('São Paulo');
    expect(mockFormInputs.uf.value).toBe('SP');
  });

  it('deve lançar erro se o CEP informado for inexistente na base do ViaCEP', async () => {
    global.fetch = vi.fn().mockResolvedValue({
      ok: true,
      json: vi.fn().mockResolvedValue({ erro: true }),
    });

    await expect(buscarCep('99999-999')).rejects.toThrow('CEP não encontrado');
  });

  it('deve validar e rejeitar CEPs com formato incorreto', async () => {
    await expect(buscarCep('123')).rejects.toThrow('CEP inválido');
  });
});