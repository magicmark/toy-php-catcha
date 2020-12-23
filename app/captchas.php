<?php

namespace Captchas {

    // Make this directory on the webserver _outside_ of the www directory.
    const CAPTCHAS_DIR = '/captchas';

    // There's probably a better way/place to do this santiy check
    // (In a real application, this should not be done at import time)
    if (!is_dir(CAPTCHAS_DIR)) {
        die("Expected " . CAPTCHAS_DIR . " to exist!");
    }

    /**
     * This function gets called on each page load of the form.
     * 
     * A random token is created (the name of the file), and its value is placed
     * inside as the contents. The "puzzle" (the value) is randomly created and
     * shown to the user.
     * 
     * Returns a tuple of:
     * - token to be embedded as a hidden form value
     * - puzzle string to display to the user
     */
    function create_token()
    {
        // Randomly generate a reasonably unguessable token. This should be good enough?
        $token = hash('sha256', random_bytes(16));

        // Generate the puzzle
        $operand_1 = rand(1, 20);
        $operand_2 = rand(1, 20);
        $operator = str_shuffle("+-")[0];
        $puzzle_string = "$operand_1 $operator $operand_2";
        $puzzle_solution = $operator == '+' ? $operand_1 + $operand_2 : $operand_1 - $operand_2;

        // Write the puzzle file to disk (so we can look it up later)
        file_put_contents(CAPTCHAS_DIR . DIRECTORY_SEPARATOR . $token, $puzzle_solution);

        return array($token, $puzzle_string);
    }

    /**
     * Given a token and an answer, validate that a file exists on disk with that
     * answer. If so, we know they completed the puzzle!
     *
     * Return: boolean
     */
    function is_token_valid($token, $user_answer) {
        /**
         * If the user supplied token is not alphanumeric, that implies they're
         * up to no good and trying to potentially read files on disk... let's
         * be super careful about checking this!
         */
        if (!preg_match("/^([0-9a-zA-Z]+)$/", $token)) {
            die('Bad token. Naughty!');
        }

        // Grab the real answer from disk.
        // In real life, wrap this with a try/catch to avoid leaking the file path
        $real_answer = file_get_contents(CAPTCHAS_DIR . DIRECTORY_SEPARATOR . $token);

        // Compare user answer vs the answer on disk
        $is_correct = $user_answer == $real_answer;

        if (!$is_correct) {
            return false;
        }

        // Clear the captcha on disk once it's been used, so an attacker
        // can't reuse the same token
        unlink(CAPTCHAS_DIR . DIRECTORY_SEPARATOR . $token);

        return true;
    }

}
