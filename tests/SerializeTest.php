<?php
namespace L2Iterative\BonsaiSDK\Tests;

use L2Iterative\BonsaiSDK\Exception;
use L2Iterative\BonsaiSDK\Serde\BinaryString;
use L2Iterative\BonsaiSDK\Serde\Boolean;
use L2Iterative\BonsaiSDK\Serde\SameTypeArray;
use L2Iterative\BonsaiSDK\Serde\Struct;
use L2Iterative\BonsaiSDK\Serde\U8;
use L2Iterative\BonsaiSDK\Serde\U16;
use L2Iterative\BonsaiSDK\Serde\U32;
use L2Iterative\BonsaiSDK\Serde\U64;
use L2Iterative\BonsaiSDK\Serde\I8;
use L2Iterative\BonsaiSDK\Serde\I16;
use L2Iterative\BonsaiSDK\Serde\I32;
use L2Iterative\BonsaiSDK\Serde\I64;
use L2Iterative\BonsaiSDK\Serde\Some;
use L2Iterative\BonsaiSDK\Serde\None;
use L2Iterative\BonsaiSDK\Serializer;
use PHPUnit\Framework\TestCase;

class SerializeTest extends TestCase {
    /**
     * @throws Exception
     */
    public function test_serialization() {
        $example = new Struct([
            'u8v' => new SameTypeArray([
                new U8(1), new U8(231), new U8(123)
            ]),
            'u16v' => new SameTypeArray([
                new U16(124), new U16(41374)
            ]),
            'u32v' => new SameTypeArray([
                new U32(14710471), new U32(3590275702), new U32(1), new U32(2)
            ]),
            'u64v' => new SameTypeArray([
                new U64(352905235952532), new U64(2147102974910410)
            ]),
            'i8v' => new SameTypeArray([
                new I8(-1), new I8(120), new I8(-22)
            ]),
            'i16v' => new SameTypeArray([
                new I16(-7932)
            ]),
            'i32v' => new SameTypeArray([
                new I32(-4327), new I32(35207277)
            ]),
            'i64v' => new SameTypeArray([
                new I64(-1), new I64(1)
            ]),
            'u8s' => new U8(3),
            'bs' => new Boolean(true),
            'some_s' => new Some(new U16(5)),
            'none_s' => new None(),
            'strings' => new BinaryString("Here is a string."),
            'stringv' => new SameTypeArray([
                new BinaryString("string a"),
                new BinaryString("34720471290497230")
            ])
        ]);

        $serializer = new Serializer();
        $serializer->serialize($example);

        $php_output = $serializer->output();
        $rust_output = [3, 1, 231, 123, 2, 124, 41374, 4, 14710471, 3590275702, 1, 2, 2, 658142100, 82167, 1578999754, 499911, 3, 4294967295, 120, 4294967274, 1, 4294959364, 2, 4294962969, 35207277, 2, 4294967295, 4294967295, 1, 0, 3, 1, 1, 5, 0, 17, 1701995848, 544434464, 1953701985, 1735289202, 46, 2, 8, 1769108595, 1629513582, 17, 842478643, 825701424, 875575602, 858928953, 48];

        $this->assertEquals($php_output, $rust_output);
    }
}

/**
 * Reference Rust code:
 *
 * ```
 * use serde::Serialize;
 *
 * #[derive(Default, Debug, Serialize)]
 * pub struct SA {
 *  pub u8v: Vec<u8>,
 *  pub u16v: Vec<u16>,
 *  pub u32v: Vec<u32>,
 *  pub u64v: Vec<u64>,
 *  pub i8v: Vec<i8>,
 *  pub i16v: Vec<i16>,
 *  pub i32v: Vec<i32>,
 *  pub i64v: Vec<i64>,
 *  pub u8s: u8,
 *  pub bs: bool,
 *  pub some_s: Option<u16>,
 *  pub none_s: Option<u32>,
 *  pub strings: String,
 *  pub stringv: Vec<String>
 * }
 *
 * fn main() {
 *  let mut test_s = SA::default();
 *  test_s.u8v = vec![1u8, 231u8, 123u8];
 *  test_s.u16v = vec![124u16, 41374u16];
 *  test_s.u32v = vec![14710471u32, 3590275702u32, 1u32, 2u32];
 *  test_s.u64v = vec![352905235952532u64, 2147102974910410u64];
 *  test_s.i8v = vec![-1i8, 120i8, -22i8];
 *  test_s.i16v = vec![-7932i16];
 *  test_s.i32v = vec![-4327i32, 35207277i32];
 *  test_s.i64v = vec![-1i64, 1i64];
 *  test_s.u8s = 3u8;
 *  test_s.bs = true;
 *  test_s.some_s = Some(5u16);
 *  test_s.none_s = None;
 *  test_s.strings = "Here is a string.".to_string();
 *  test_s.stringv = vec!["string a".to_string(), "34720471290497230".to_string()];
 *
 *  let mut res = Vec::<u32>::new();
 *  let mut serializer = risc0_zkvm::serde::Serializer::new(&mut res);
 *  let _ = test_s.serialize(&mut serializer);
 *
 *  eprintln!("{:?}", res);
 *
 *  println!("Hello, world!");
 * }
 * ```
 */