// server/routes/testes.js
import { startVitest } from 'vitest/node';
import express from 'express';

const router = express.Router();

router.post('/api/run-tests', async (req, res) => {
  try {
    // Inicia a instância do Vitest programaticamente
    const vitest = await startVitest('test', [], {
      run: true,             // Executa uma vez e finaliza (sem modo watch)
      reporter: ['json'],    // Define o reporter JSON
      outputFile: './test-results.json' // Salva o relatório
    });

    // Aguarda a finalização dos testes
    await vitest?.close();

    // Lê o relatório JSON gerado
    const fs = await import('fs/promises');
    const rawData = await fs.readFile('./test-results.json', 'utf-8');
    const results = JSON.parse(rawData);

    // Mapeia os dados do JSON para o formato exibido na sua interface
    const summary = {
      testSuites: results.numTotalTestSuites,
      passSuites: results.numPassedTestSuites,
      totalTests: results.numTotalTests,
      passedTests: results.numPassedTests,
      duration: ((results.endTime - results.startTime) / 1000).toFixed(1),
      testResults: results.testResults.map(suite => ({
        name: suite.name.split('/').pop(), // Nome do arquivo spec
        status: suite.status,
        duration: suite.endTime - suite.startTime,
        assertionResults: suite.assertionResults
      }))
    };

    res.json({ success: true, data: summary });
  } catch (error) {
    res.status(500).json({ success: false, error: error.message });
  }
});

export default router;