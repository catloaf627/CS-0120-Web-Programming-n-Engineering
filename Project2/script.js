/*********************************** CLASSES ***********************************/

class WordleStats {
  constructor() {
    this.wins = parseInt(this.getCookie('wordleWins') || '0', 10);
    this.totalGuesses = parseInt(this.getCookie('wordleGuessTotal') || '0', 10);
    this.render();
  }

  setCookie(name, value, days = 365) {
    const expires = new Date(Date.now() + days * 24 * 60 * 60 * 1000).toUTCString();
    document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/`;
  }

  getCookie(name) {
    const regex = new RegExp('(?:^|; )' + name.replace(/[-.$?*|{}()[\]\\/+^]/g, '\\$&') + '=([^;]*)');
    const match = document.cookie.match(regex);
    return match ? decodeURIComponent(match[1]) : null;
  }

  addGame(guessCount) {
    this.wins += 1;
    this.totalGuesses += guessCount;
    this.setCookie('wordleWins', this.wins);
    this.setCookie('wordleGuessTotal', this.totalGuesses);
    this.render();
  }

  clear() {
    this.wins = 0;
    this.totalGuesses = 0;
    this.setCookie('wordleWins', '', -1);
    this.setCookie('wordleGuessTotal', '', -1);
    this.render();
  }

  render() {
    const avg = this.wins > 0 ? (this.totalGuesses / this.wins).toFixed(2) : '—';
    document.getElementById('avgGuesses').textContent = avg;
  }
}

const stats = new WordleStats();




/*********************************** FUNCTIONS ***********************************/

// Generate an answer
let useAPI = false;
let answer = "";

function pickAnswer() {
  if (!useAPI) {
    const words = [
      "ARRAY", "LOGIC", "STACK", "QUEUE", "INPUT", "TOKEN", "CACHE", "BUGGY", "SPACE", "BYTES",
      "INDEX", "CLASS", "FLOAT", "LOGIN", "LEARN", "FILES", "STUDY", "RECUR", "QUERY", "PROTO",
      "BASIC", "VIRUS", "CLOUD", "ROBOT", "PIXEL", "ALERT", "DEBUG", "FRAME", "PATCH", "BRAIN"
    ];
    answer = words[Math.floor(Math.random() * words.length)];
    console.log("🌸Answer from Array:", answer);
  } else {
    fetch("https://random-word-api.herokuapp.com/word?length=5")
      .then(res => res.json())
      .then(data => {
        answer = data[0].toUpperCase();
        console.log("🌸Answer from API:", answer);
      });
  }
}

// Clear board and keyboard, switch (restartBtn) back to sumbitBtn
function clearEverything() {
  document.getElementById("guessInput").value = "";
  document.getElementById("message").textContent = "";
  document.querySelectorAll(".cell").forEach(cell => { // clear board
    cell.textContent = "";
    cell.style.background = "white";
  });
  document.querySelectorAll("#keyboard button").forEach(btn => { // clear keyboard
    btn.style.background = "";
    btn.style.color = "";
  });
  document.getElementById("restartBtn").style.display = "none"; // switch button
  document.getElementById("submitBtn").style.display = "inline-block";
  document.getElementById("guessInput").style.display = "inline-block";
}

pickAnswer(); // Generate an answer after the page is loaded (without hitting the restartBtn!)




// Create the game board dynamically
const board = document.getElementById("board");
for (let i = 0; i < 6; i++) {
  const row = document.createElement("div");
  row.className = "row";
  for (let j = 0; j < 5; j++) {
    const cell = document.createElement("div");
    cell.className = "cell";
    cell.id = `cell-${i}-${j}`;
    row.appendChild(cell);
  }
  board.appendChild(row);
}




/*********************************** ACTIONS ***********************************/

// Click api-switch
document.getElementById("apiSwitch").addEventListener("change", (e) => {
  useAPI = e.target.checked;
  pickAnswer();
  clearEverything();
});

// Restart button logic (change answer & clear board)
document.getElementById("restartBtn").addEventListener("click", () => {
  pickAnswer();
  clearEverything();
});

// Make on-screen keyboard buttons type into the input
document.querySelectorAll("#keyboard button").forEach(btn => {
  btn.addEventListener("click", () => {
    const input = document.getElementById("guessInput");
    const key = btn.textContent;
    if (btn.id == "enterBtn") {
      document.getElementById("submitBtn").click();
      return;
    }
    if (btn.id == "deleteBtn") {
      input.value = input.value.slice(0, -1);
      return;
    }
    if (input.value.length < 5 && key.length === 1) {
      input.value += key;
    }
  });
});

// Clear cookie button
document.getElementById("clearStatsBtn").addEventListener("click", () => {
  stats.clear();
});




// MAIN LOGIC HERE (click submit)
let currentRow = 0;
document.getElementById('submitBtn').onclick = () => {
  // Length error
  const guess = document.getElementById('guessInput').value.trim().toUpperCase();
  if (guess.length !== 5) {
    alert("Word must be 5 letters!");
    return;
  }

  // Show your guess on board (with color)
  let numCorrectLetters = 0;
  for (let i = 0; i < 5; i++) {
    const cell = document.getElementById(`cell-${currentRow}-${i}`);
    cell.textContent = guess[i];
    const letter = guess[i];
    if (letter === answer[i]) {
      cell.style.background = "green";
      document.querySelectorAll("#keyboard button").forEach(btn => { if (btn.textContent === letter) btn.style.background = "green"; });
      numCorrectLetters++;
    } else if (answer.includes(letter)) {
      cell.style.background = "yellow";
      document.querySelectorAll("#keyboard button").forEach(btn => { if (btn.textContent === letter) btn.style.background = "yellow"; });
    } else {
      cell.style.background = "gray";
      document.querySelectorAll("#keyboard button").forEach(btn => { if (btn.textContent === letter) btn.style.background = "gray"; });
    }
  }

  // Game over
  if (numCorrectLetters == 5) { // SUCCESS!
    stats.addGame(currentRow + 1);
    currentRow = 0;
    document.getElementById("guessInput").style.display = "none";
    document.getElementById("submitBtn").style.display = "none";
    document.getElementById("restartBtn").style.display = "inline-block";
    setTimeout(() => { alert("✅✅✅  '" + answer + "'  ✅✅✅"); }, 100); // wait for the board and the keyboard to update.
    return;
  }

  if (currentRow == 5) { // FAIL!
    currentRow = 0;
    document.getElementById("guessInput").style.display = "none";
    document.getElementById("submitBtn").style.display = "none";
    document.getElementById("restartBtn").style.display = "inline-block";
    setTimeout(() => { alert("❌❌❌  '" + answer + "'  ❌❌❌"); }, 100); // wait for the board and the keyboard to update.
    return;
  }

  // Game NOT over, continue to next line
  currentRow++;
  document.getElementById('guessInput').value = "";
};
