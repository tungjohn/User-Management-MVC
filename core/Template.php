<?php
class Template {

    private $__content = '';

    public function run($content, $data = []) {
        extract($data);
        $this->__content = $content;
        if (!empty($content)) {
            // Các biến {{ $var }} sẽ được thay thế bằng <?php echo htmlentities($var)
            $this->printEntitites();

            // Các biến {{ $var }} sẽ được thay thế bằng <?php echo $var
            $this->printRaw();

            $this->ifCondition();

            $this->phpBegin();
            $this->phpEnd();

            $this->foreachLoop();
            $this->forLoop();
            $this->whileLoop();
            // switch case

            eval(' ?>' . $this->__content . '<?php ');
        }
        
    }

    public function printEntitites() {
        preg_match_all('/{{\s*(.+?)\s*}}/', $this->__content, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php echo htmlentities(' . $value . '); ?>', $this->__content);
            }
        }
    }

    public function printRaw() {
        preg_match_all('/{!\s*(.+?)\s*!}/', $this->__content, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php echo ' . $value . '; ?>', $this->__content);
            }
        }
    }

    public function ifCondition() {
        preg_match_all('/@elseif\s*(\((.+?)\)+)\s*\n/', $this->__content, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php elseif ' . $value . ':  ?>', $this->__content);
            }
        }

        preg_match_all('/@if\s*(\((.+?)\)+)\s*\n/', $this->__content, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php if ' . $value . ' : ?>', $this->__content);
            }
        }

        preg_match_all('/@else\s*/', $this->__content, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php else:  ?>', $this->__content);
            }
        }

        preg_match_all('/@endif\s*/', $this->__content, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php endif; ?>', $this->__content);
            }
        }
    }

    public function phpBegin() {
        preg_match_all('/@php\s*/', $this->__content, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php ', $this->__content);
            }
        }
    }

    public function phpEnd() {
        preg_match_all('/@endphp\s*/', $this->__content, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], ' ?>', $this->__content);
            }
        }
    }
    
    public function forLoop() {
        preg_match_all('/@for\s*(\((.+?)\)+)\s*/', $this->__content, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php for ' . $value . ' : ?>', $this->__content);
            }
        }

        preg_match_all('/@endfor\s*/', $this->__content, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php endfor; ?>', $this->__content);
            }
        }
    }

    public function whileLoop() {
        preg_match_all('/@while\s*(\((.+?)\)+)\s*/', $this->__content, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php while ' . $value . ' : ?>', $this->__content);
            }
        }

        preg_match_all('/@endwhile\s*/', $this->__content, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php endwhile; ?>', $this->__content);
            }
        }
    }

    public function foreachLoop() {
        preg_match_all('/@foreach\s*(\((.+?)\)+)\s*/', $this->__content, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php foreach ' . $value . ' : ?>', $this->__content);
            }
        }

        preg_match_all('/@endforeach\s*/', $this->__content, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php endforeach; ?>', $this->__content);
            }
        }
    }

}