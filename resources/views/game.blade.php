@extends('layoutsuser.head')

@section('title', 'GAME')

@section('content')
    <style>
        <style>

        /* Animation properties */
        .star {
            animation: star 10s ease-out infinite;
        }

        .wars {
            animation: wars 10s ease-out infinite;
        }

        .byline span {
            animation: spin-letters 10s linear infinite;
        }

        .byline {
            animation: move-byline 10s linear infinite;
        }

        /* Keyframes */
        @keyframes star {
            0% {
                opacity: 0;
                transform: scale(1.5) translateY(-0.75em);
            }

            20% {
                opacity: 1;
            }

            89% {
                opacity: 1;
                transform: scale(1);
            }

            100% {
                opacity: 0;
                transform: translateZ(-1000em);
            }
        }

        @keyframes wars {
            0% {
                opacity: 0;
                transform: scale(1.5) translateY(0.5em);
            }

            20% {
                opacity: 1;
            }

            90% {
                opacity: 1;
                transform: scale(1);
            }

            100% {
                opacity: 0;
                transform: translateZ(-1000em);
            }
        }

        @keyframes spin-letters {

            0%,
            10% {
                opacity: 0;
                transform: rotateY(90deg);
            }

            30% {
                opacity: 1;
            }

            70%,
            86% {
                transform: rotateY(0);
                opacity: 1;
            }

            95%,
            100% {
                opacity: 0;
            }
        }

        @keyframes move-byline {
            0% {
                transform: translateZ(5em);
            }

            100% {
                transform: translateZ(0);
            }
        }

        /* Make the 3D work on the container */
        .starwars-demo {
            perspective: 800px;
            transform-style: preserve3d;
        }

        /* General styles */
        body {
            background: #000 url(//cssanimation.rocks/demo/starwars/images/bg.jpg);
        }

        .starwars-demo {
            height: 17em;
            left: 50%;
            position: absolute;
            top: 53%;
            transform: translate(-50%, -50%);
            width: 34em;
        }

        .byline span {
            display: inline-block;
        }

        img {
            width: 100%;
        }

        .star,
        .wars,
        .byline {
            position: absolute;
        }

        .star {
            top: -0.75em;
        }

        .wars {
            bottom: -0.5em;
        }

        .byline {
            color: #fff;
            font-family: "ITC Serif Gothic", Lato;
            font-size: 2.25em;
            left: -2em;
            letter-spacing: 0.4em;
            right: -2em;
            text-align: center;
            text-transform: uppercase;
            top: 29%;
        }

        /* Media Queries for small screens */
        @media only screen and (max-width: 600px) {
            .starwars-demo {
                font-size: 10px;
            }
        }

        @media only screen and (max-width: 480px) {
            .starwars-demo {
                font-size: 7px;
            }
        }

        .container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0 20px;
            max-width: 100%;
            width: 100%;
        }

        .game-area,
        .snake-game-area {
            flex: 0 0 45%;
            max-width: 500px;
            margin: 0;
            padding: 20px;
            background-color: #0e0e0f31;
            border: 2px solid white;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }


        .board {
            display: grid;
            grid-template-columns: repeat(3, 100px);
            gap: 10px;
            margin: 20px auto;
        }

        .cell {
            width: 100px;
            height: 100px;
            font-size: 40px;
            text-align: center;
            border: 2px solid white;
            background-color: #ffffff;
            color: #343a40;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cell:hover {
            background-color: white;
            color: white;
            transform: scale(1.05);
        }

        .cell:disabled {
            background-color: #e9ecef;
            color: #6c757deb;
            cursor: not-allowed;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: white;
            font-size: 28px;
            font-weight: bold;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            color: white;
        }

        .new-game-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 18px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .new-game-btn:hover {
            background-color: #0056b3;
        }

        .image-area {
            text-align: center;
            margin: 0 20px;
        }

        .animated-image {
            width: 300px;
            height: auto;
            animation: shake 1s infinite;
        }

        @keyframes shake {
            0% {
                transform: translate(1px, 0);
            }

            25% {
                transform: translate(-1px, 0);
            }

            50% {
                transform: translate(1px, 0);
            }

            75% {
                transform: translate(-1px, 0);
            }

            100% {
                transform: translate(1px, 0);
            }
        }

        /* Snake game area styling */
        #game-board {
            width: 400px;
            height: 400px;
            background-color: #33333338;
            position: relative;
            margin: 0 auto;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid white;
        }

        .snake {
            background-color: #4caf50;
            position: absolute;
            width: 20px;
            height: 20px;
        }

        .food {
            background-color: red;
            position: absolute;
            width: 20px;
            height: 20px;
        }

        #score {
            margin-top: 20px;
            text-align: center;
            font-size: 18px;
            color: white;
        }

        .start-btn {
            padding: 10px 20px;
            font-size: 18px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 20px auto;
        }

        .start-btn:hover {
            background-color: #0056b3;
        }

        @media only screen and (max-width: 1200px) {
            .container {
                flex-direction: column;
                align-items: center;
            }

            .game-area,
            .snake-game-area {
                flex: 0 0 100%;
                margin-bottom: 20px;
            }

            .image-area {
                order: 3;
                margin-top: 20px;
            }
        }

        header,
        footer {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            position: fixed;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.8);
            /* Semi-transparent background */
            color: white;
            padding: 10px;
            z-index: 1000;
        }

        header {
            top: -50px;
            /* Hide it above the viewport */
            transform: translateY(0);
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }

        footer {
            bottom: -50px;
            /* Hide it below the viewport */
            transform: translateY(0);
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }

        header:hover,
        footer:hover {
            opacity: 1;
        }

        header:hover {
            transform: translateY(50px);
        }

        footer:hover {
            transform: translateY(-50px);
        }

        /* Adjust main content to take full height */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 50px;
            /* Add padding to account for header */
            padding-bottom: 50px;
            /* Add padding to account for footer */
        }

        main {
            flex-grow: 1;
        }
    </style>

    <!-- Star Wars Animation -->
    <div class="starwars-demo">
        <img src="//cssanimation.rocks/demo/starwars/images/star.svg" alt="Star" class="star">
        <img src="//cssanimation.rocks/demo/starwars/images/wars.svg" alt="Wars" class="wars">
        <h2 class="byline" id="byline">The Force Awakens</h2>
    </div>

    <div class="container mt-5">
        <!-- GAME X/O -->
        <div class="game-area">
            <h1>GAME X || O</h1>

            @if (session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if (isset($message))
                <p class="message">{{ $message }}</p>
            @endif

            <div class="board">
                @foreach ($board as $index => $value)
                    <form method="POST" action="{{ url('/move') }}">
                        @csrf
                        <input type="hidden" name="position" value="{{ $index }}">
                        <button class="cell" type="submit" {{ $value ? 'disabled' : '' }}>
                            {{ $value }}
                        </button>
                    </form>
                @endforeach
            </div>

            <form method="GET" action="{{ route('game') }}">
                <button type="submit" class="new-game-btn">เริ่มเกมใหม่</button>
            </form>
        </div>

        <!-- Animated image in the center -->
        {{-- <div class="image-area">
        <img src="{{ asset('img/012.gif') }}" class="animated-image" alt="012" />
        <img src="{{ asset('img/011.gif') }}" class="animated-image" alt="011" />
    </div> --}}

        <!-- SNAKE GAME -->
        <div class="snake-game-area">
            <h1>Snake Game</h1>
            <div id="game-board"></div>
            <button id="start-game" class="start-btn">เริ่มเกม</button>
            <p id="score">Score: 0</p>
        </div>
    </div>

    <script>
        const boardSize = 400;
        const blockSize = 20;
        const board = document.getElementById('game-board');
        let snake = [{
            x: 200,
            y: 200
        }];
        let food = {
            x: 100,
            y: 100
        };
        let direction = {
            x: 0,
            y: 0
        };
        let score = 0;
        let gameInterval;

        function createBlock(x, y, className) {
            const block = document.createElement('div');
            block.style.left = `${x}px`;
            block.style.top = `${y}px`;
            block.classList.add(className);
            board.appendChild(block);
        }

        function placeFood() {
            food.x = Math.floor(Math.random() * (boardSize / blockSize)) * blockSize;
            food.y = Math.floor(Math.random() * (boardSize / blockSize)) * blockSize;
            createBlock(food.x, food.y, 'food');
        }

        function updateSnake() {
            const newHead = {
                x: snake[0].x + direction.x * blockSize,
                y: snake[0].y + direction.y * blockSize
            };

            if (
                newHead.x < 0 || newHead.x >= boardSize ||
                newHead.y < 0 || newHead.y >= boardSize ||
                snake.some(segment => segment.x === newHead.x && segment.y === newHead.y)
            ) {
                alert('Game Over');
                clearInterval(gameInterval);
                window.location.reload();
            }

            snake.unshift(newHead);
            if (newHead.x === food.x && newHead.y === food.y) {
                score += 10;
                document.getElementById('score').textContent = `Score: ${score}`;
                placeFood();
            } else {
                snake.pop();
            }

            board.innerHTML = '';
            snake.forEach(segment => createBlock(segment.x, segment.y, 'snake'));
            createBlock(food.x, food.y, 'food');
        }

        window.addEventListener('keydown', event => {
            if (event.key === 'ArrowUp' && direction.y === 0) direction = {
                x: 0,
                y: -1
            };
            else if (event.key === 'ArrowDown' && direction.y === 0) direction = {
                x: 0,
                y: 1
            };
            else if (event.key === 'ArrowLeft' && direction.x === 0) direction = {
                x: -1,
                y: 0
            };
            else if (event.key === 'ArrowRight' && direction.x === 0) direction = {
                x: 1,
                y: 0
            };
        });

        document.getElementById('start-game').addEventListener('click', () => {
            direction = {
                x: 1,
                y: 0
            };
            placeFood();
            gameInterval = setInterval(updateSnake, 200);
            document.getElementById('start-game').disabled = true;
        });
        document.addEventListener('DOMContentLoaded', function() {
            document.body.style.cursor = 'none';

            document.body.addEventListener('mousemove', function() {
                document.body.style.cursor = 'auto';
            });
        });
    </script>
@endsection
