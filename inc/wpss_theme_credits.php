<?php
/**
 * @author              Angelo Rocha
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 */

function wpss_theme_credits() {
	$author_credits = "";
	$author_url     = "https://angelorocha.com.br";
	$image          = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACkAAAAjCAYAAAAJ+yOQAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAK6wAACusBgosNWgAAABZ0RVh0Q3JlYXRpb24gVGltZQAwNy8wNS8xN2vbjT4AAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzbovLKMAAADB0lEQVRYhbWYW6hMURjHfzNuJeQWkeJFFMmLOg88UZSi3ClLbnlYiU4shdwdrAcRK8UhrSI8KJQob5IonlA8mBQKCeUul4c9M82Zs/f+9tqz/Z9m7+//feu3v5nWt2aXpi3cRCvyVvcGKsDImPAvYJcyrqOVNcqtJAMo434CLiHcC9jvrT7fyhotQ1Z1AviZEl/qrV6St3ghkMq4D8BZweaqP41gFdVJgENCfDCwIk/hwiCVcc+BG4Jtm7e6FFq7yE4C7BPio4FZoUULhVTG3QEeC7YdoXWL7iTAbiHe5q2eFFLwf0BeBt4Jnu0hBQuHVMb9Bg4Ktvne6rgJFav/0UmAk8B3Yd2NWYuVssxub/UAYAzZH+o1sBXYkOL5CgxTxn2RivUU4MpAB9BONIezajXRBFpP8oP1BVYCx6ViUmfWAFsIAwQ4DfQHrgq+rdVGpEoyrM9K1aSnyrjbyJv7CGCOVCwR0lvdBkwMY6urE0AZ9wB4KHh3SsXSOrkmAKpRvwAfADHZWz0lzRAL6a3uDywOY6vrmjLubcP1deCVkJM6KpM6uQjoFwDWqM7GC2XcH2C/kDPbWz06KZgEmferfgncjLl/FkjbD0tA4obdDdJbPQFoC4Sr6Uy1c12kjPsGHBNy11aHRjfFdTJvF/8CZ1LiR4DfKfE+wLq4QBdIb3UfYHkoXVW3lHEvkoLKuDfARaGG8VZ3m4LNnZwLDAnnA+BUBs8BIT4UWNB8sxlybVaiJr0HrkgmZdwj4I5g67av1iG91WOA6YFw9fTqS4Iskk7u473VUxtvNHZyFdFWkEedsqWuW0SvZdLUpZtlAG91D6JjUx7dVcY9yWpWxv0F9gq2Gd7qsbWLWidnAqPC+YDoWBaqc8AnwbOl9qEGuTrHQgCfgQuhSdXf72HJ5q0eDFCqVCrDicZZ6ik9QZ1ETxw7KQQNAu4L6+5Uxu0pVSqVzYDNsQhE43Mp6f9lWtFHYHiZ/GPwtjLuXoFAcRoILCsr48YBzwISfwCXgHkAyriNwNHi+epq/wejH7QNc/kGGQAAAABJRU5ErkJggg==";

	$html = "<a href='$author_url' title='$author_credits' target='_blank'><img src='$image' alt='$author_credits'></a>";

	return $html;
}