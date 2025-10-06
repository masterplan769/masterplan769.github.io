class MyBrain {
  constructor() {
    this.patterns = [];
  }

  train(data) {
    this.patterns = data
      .filter(d => d.input && d.output)
      .map(d => ({
        input: d.input.toLowerCase().trim(),
        output: d.output.trim()
      }));
  }

  run(text) {
    text = text.toLowerCase().trim();
    for (let p of this.patterns) {
      if (text === p.input) return p.output;
    }
    return "Nem értem. Tudnál máshogy fogalmazni?";
  }

  loadFromURL(url) {
    return fetch(url)
      .then(res => res.text())
      .then(text => {
        const trainingData = text.split('\n').map(line => {
          const [input, output] = line.split('|');
          return { input, output };
        });
        this.train(trainingData);
        console.log('AI betanítva:', trainingData.length, 'beszélgetés mintával');
      });
  }
}
