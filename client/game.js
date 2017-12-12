function Game(id){
	var clientUrl = window.location.href;
	var projectUrl = clientUrl.replace('client/', '');
	var baseUrl = projectUrl + 'server/game.php';
	var elem = document.getElementById(id);

	var cells = {
		count: null,
		elem: null,
		items: null,
	}

	var score = {
		moves: null,
		elem: null
	}

	var init = () => {
		send('getCellsCount', null, (text) => {
			cells.count = text;
			send('isRun', null, (text) => {
				if (Boolean(text) === true) {
					renderGame();
					send('getOpened', null, (text) => {
						renderMove(text);
					});
				} else {
					renderRestart();
				}
			});
		});
	};

	var start = () => {
		send('start', null, renderGame);
	};

	var stop = () => {
		send('stop', null, renderRestart);
	};

	var move = (cell) => {
		send('move', {'cell':cell}, renderMove);
	};

	var send = (command, params, callback) => {
		data = {'command': command};
		if (typeof params === 'object') Object.assign(data, params);
		ajax.get(baseUrl, data, callback);
	};

	var renderMove = (json) => {
		console.log(json);
		var data = JSON.parse(json);
		for (var i = 0; i < data.length; i++) {
			var move = data[i];
			if (!move) continue;
			if (Boolean(move.repeat) === true) return console.log('repeat plz');
			if (typeof move.cell !== undefined) {
				let cell = cells.items[move.cell];
				cell.classList.add('opened');
				cell.innerHTML = move.player;
			}
			if (move.prize) {
				score.elem.innerHTML += '<li class="prize">' + move.prize + '</li>';
			}
			score.moves.push(move);
			if (Boolean(move.restart) === true) {
				cells.items[move.cell].classList.add('exit');
				stop();
			}
			renderOldMove();
		}
	};

	var renderOldMove = () => {
		// console.log(score.moves);
		var ind = score.moves.length - 3;
		var lim = 1;
		if (ind % 2 == 0) {
			lim = 2;
		}
		for (var i = 0; i < lim; i++) {
			// console.log(ind);
			if (ind >= 0) {
				var move = score.moves[ind++];
				cells.items[move.cell].classList.add('old');
			}
		}
	};

	var renderGame = () => {
		cells.items = [];
		score.moves = [];
		elem.innerHTML = '';
		cells.elem = document.createElement('ul');
		cells.elem.className = 'cells';
		for (var i = 0; i < cells.count; i++) {
			var cell = document.createElement('li');
			cell.className = 'cell';
			cell.onclick = move.bind(this, i);
			cells.items.push(cell);
			cells.elem.appendChild(cell);
		}
		elem.appendChild(cells.elem);
		score.elem = document.createElement('ul');
		score.elem.className = 'score';
		elem.appendChild(score.elem);
		score.elem.innerHTML += '<li class="player">User</li><li class="player">Bot</li>';
	};

	var renderRestart = () => {
		if (cells.items) {
			for (var i = 0; i < cells.count; i++) {
				cells.items[i].onclick = null;
			}
		}
		var btn = document.createElement('button');
		btn.innerText = 'Start';
		btn.className = 'start-btn';
		btn.onclick = start;
		elem.appendChild(btn); 
	};

	return {
		'init': 	init,
		'start': 	start,
		'stop': 	stop,
		'move': 	move
	};
}