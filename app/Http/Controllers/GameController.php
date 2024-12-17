<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        $board = array_fill(0, 9, '');
        session(['board' => $board, 'current_player' => 'X']);
        return view('game', [
            'board' => $board,
            'message' => 'เริ่มเกมใหม่! ผู้เล่น X เริ่มก่อน'
        ]);
    }

    public function move(Request $request)
    {
        $position = $request->input('position');
        $board = session('board');
        $currentPlayer = session('current_player');

        if ($board[$position] === '') {
            $board[$position] = $currentPlayer;
            $winner = $this->checkWinner($board);

            if ($winner) {
                $message = "ผู้เล่น $winner ชนะ!";
                session(['board' => array_fill(0, 9, ''), 'current_player' => 'X']);
                return view('game', [
                    'board' => $board,
                    'message' => $message,
                    'isGameOver' => true
                ]);
            } elseif (!in_array('', $board)) {
                $message = "เสมอ!";
                session(['board' => array_fill(0, 9, ''), 'current_player' => 'X']);
                return view('game', [
                    'board' => $board,
                    'message' => $message,
                    'isGameOver' => true
                ]);
            } else {
                $currentPlayer = ($currentPlayer === 'X') ? 'O' : 'X';
                session(['current_player' => $currentPlayer]);
                $message = "ถึงตาผู้เล่น $currentPlayer";
            }

            session(['board' => $board]);
        } else {
            $message = "ตำแหน่งนี้ถูกใช้แล้ว กรุณาเลือกตำแหน่งอื่น";
        }

        return view('game', ['board' => $board, 'message' => $message]);
    }

    private function checkWinner($board)
    {
        $lines = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8], // แนวนอน
            [0, 3, 6], [1, 4, 7], [2, 5, 8], // แนวตั้ง
            [0, 4, 8], [2, 4, 6] // แนวทแยง
        ];

        foreach ($lines as $line) {
            if ($board[$line[0]] !== '' &&
                $board[$line[0]] === $board[$line[1]] &&
                $board[$line[1]] === $board[$line[2]]) {
                return $board[$line[0]];
            }
        }

        return null;
    }
}
