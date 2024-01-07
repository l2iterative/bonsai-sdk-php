<?php

namespace L2Iterative\BonsaiSDK\Serde;

/**
 * A class representing a byte buffer that should be serialized compactly
 */
class BinaryString implements SerializeInterface
{

    /**
     * The binary string
     *
     * @var string
     */
    public string $val;


    /**
     * A constructor that takes a string
     *
     * @param string $val The binary string.
     */
    public function __construct(string $val)
    {
        $this->val = $val;

    }//end __construct()


    /**
     * Serialize a binary string compactly by representing it as an array of u32
     *
     * @param array $buffer A reference to the buffer.
     *
     * @return void
     */
    public function write(array &$buffer): void
    {
        $buffer[] = strlen($this->val);

        $num_group  = floor((strlen($this->val) + 3) / 4);
        $padded_len = ($num_group * 4);
        $padded_str = str_pad($this->val, $padded_len, "\x00");

        for ($i = 0; $i < $num_group; $i++) {
            $group    = substr($padded_str, ($i * 4), 4);
            $buffer[] = unpack('V', $group)[1];
        }

    }//end write()


}//end class
