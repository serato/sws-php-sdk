<?php
declare(strict_types=1);

namespace Serato\SwsSdk;

use DateTime;

/**
 * Class FirewallHeader
 *
 * Represents a strategy for generating a header that identifies Serato applications to the firewall. This header should
 * be non-trivial to guess by outsiders (unless they find this repository). If we wanted to make this header harder to
 * guess, we could introduce an environment variable that isn't present in the source code.
 *
 * @package Serato\SwsSdk
 */
class FirewallHeader
{
    /**
     * The values by which ordinal values of the ASCII characters in each 8-character chunk of the md5 hash will be
     * shifted (with the intention of making the strategy for generating the header less guessable, while still allowing
     * the header to be easily matched by a regular expression)
     */
    public const SHIFTS = [-8, 8, -16, 16];

    /**
     * In another effort to make the header less guessable by people curious enough to make requests to our test servers
     * (but not curious enough to look at this open source code), add a prefix with characters drawn from a specific set
     * to the header
     */
    public const PREFIX_CHARACTERS = 'serato';

    /**
     * Regular expression pattern that will match valid firewall header lines
     */
    public const HEADER_PATTERN = '/[serato]{3}~[\x28-\x31\x59-\x5E]{8}[\x38-\x41\x69-\x6E]{8}[\x20-\x29\x51-\x56]{8}[\x40-\x49\x71-\x76]{8}/';

    /**
     * @var string Date/time at which this header was created (used to create the hash)
     */
    private $timeStamp;

    /**
     * @var string Three letter prefix for the firewall header, from the set of PREFIX_CHARACTERS letters
     */
    private $prefix;

    public function __construct()
    {
        $this->prefix = substr(str_shuffle(self::PREFIX_CHARACTERS),-3);
        $this->timeStamp = (new DateTime())->format(DateTime::ATOM);
    }

    /**
     * Returns a header value consisting of:
     * 1. A 3 character prefix drawn from characters in the PREFIX_CHARACTERS string with no repeats, and
     * 2. A hash, with every 8 ASCII character chunk shifted by the offsets defined in the SHIFTS array
     * - separated by a ~ character.
     * @return string Header value, for example 'rta~Y[)(/*\,:ijkk>k:S!#R((U$tGvuIstE'
     */
    public function getHeaderValue(): string
    {
        $hash = $this->getHeaderHash($this->timeStamp);

        return $this->prefix . '~' . $hash;
    }

    private function getHeaderHash(string $textToHash): string
    {
        $hash = md5($textToHash);
        $chunks = str_split($hash, 8);
        $shiftedHash = '';

        // Shift each chunk's ASCII character values by the corresponding offset in the SHIFTS array
        foreach ($chunks as $i => $chunk) {
            $shiftedHash .= $this->shiftChunk($chunk, self::SHIFTS[$i]);
        }

        return $shiftedHash;
    }

    private function shiftChunk(string $chunk, int $shift): string
    {
        $shiftedChunk = $chunk;

        // Shifts the chunk's (ASCII) characters by the given offset
        for ($i = 0, $iMax = strlen($chunk); $i < $iMax; $i++) {
            $shiftedChunk[$i] = $this->shiftCharacter($chunk[$i], $shift);
        }

        return $shiftedChunk;
    }

    private function shiftCharacter(string $character, int $shift): string
    {
        return chr(ord($character) + $shift);
    }
}
