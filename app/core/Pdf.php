<?php
declare(strict_types=1);

final class Pdf
{
    private float $pageW = 612;
    private float $pageH = 792;
    private float $margin = 50;
    private array $pages = [];
    private string $title;
    private string $subtitle;

    public function __construct(string $title = 'Report', string $subtitle = '')
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->pages[] = [];
    }

    private function page(): array
    {
        return $this->pages[array_key_last($this->pages)];
    }

    public function addPage(): void
    {
        $this->pages[] = [];
    }

    private function write(string $cmd, string $args = ''): void
    {
        $this->pages[array_key_last($this->pages)][] = $cmd . ($args !== '' ? ' ' . $args : '');
    }

    public function text(string $text, float $size = 10, bool $bold = false, int $align = 0, float $y = 0): void
    {
        $text = $this->clean($text);
        $font = $bold ? '/F2' : '/F1';
        $w = $this->strWidth($text, $size);
        $x = $this->margin;
        if ($align === 1) {
            $x = ($this->pageW - $w) / 2;
        } elseif ($align === 2) {
            $x = $this->pageW - $this->margin - $w;
        }
        $this->write('BT');
        $this->write(sprintf('%s %.1f Tf', $font, $size));
        $this->write(sprintf('1 0 0 1 %.1f %.1f Tm', $x, ($y > 0 ? $y : $this->currentY()) ));
        $this->write('(' . $this->escape($text) . ') Tj');
        $this->write('ET');
    }

    public function cellText(string $text, float $x, float $y, float $size, bool $bold = false): void
    {
        $text = $this->clean(mb_substr($text, 0, 40));
        $font = $bold ? '/F2' : '/F1';
        $this->write('BT');
        $this->write(sprintf('%s %.1f Tf', $font, $size));
        $this->write(sprintf('1 0 0 1 %.1f %.1f Tm', $x, $y));
        $this->write('(' . $this->escape($text) . ') Tj');
        $this->write('ET');
    }

    public function rect(float $x, float $yTop, float $w, float $h, string $fill = '0 0 0'): void
    {
        $this->write('q');
        $this->write($fill . ' rg');
        $this->write(sprintf('%.1f %.1f %.1f %.1f re f', $x, $yTop, $w, $h));
        $this->write('Q');
    }

    public function line(float $x1, float $y1, float $x2, float $y2, string $color = '0.2 0.4 0.8', float $width = 1): void
    {
        $this->write('q');
        $this->write($color . ' RG');
        $this->write(sprintf('%.2f w', $width));
        $this->write(sprintf('%.1f %.1f m %.1f %.1f l S', $x1, $y1, $x2, $y2));
        $this->write('Q');
    }

    public function headerBlock(float $y): void
    {
        $this->text($this->title, 17, true, 0, $y);
        $this->text($this->subtitle, 10, false, 1, $y - 18);
        $this->line($this->margin, $y - 28, $this->pageW - $this->margin, $y - 28, '0.96 0.65 0.04', 2);
    }

    public function footerBlock(int $pageNo, int $total): void
    {
        $y = 40;
        $this->text('Coin_Nex - NextGen BudgetBee', 8, true, 0, $y);
        $this->text('Page ' . $pageNo . ' of ' . $total, 8, false, 2, $y);
    }

    public function table(array $headers, array $rows, array $widths, float $y, array $aligns = []): float
    {
        $totalW = array_sum($widths);
        $dx = ($this->pageW - 2 * $this->margin - $totalW) / 2;
        $headingH = 14;
        $rowH = 13;
        $x0 = $this->margin + $dx;

        $this->rect($x0, $y, $totalW, $headingH, '0.11 0.13 0.18');
        $cx = $x0;
        foreach ($headers as $i => $h) {
            $align = $aligns[$i] ?? 0;
            $this->cellText($h, $cx + 4, $y + 4.2, 9, true);
            if ($align === 2) {
                $this->cellText($h, $cx + $widths[$i] - $this->strWidth((string)$h, 9) - 4, $y + 4.2, 9, true);
            }
            $cx += $widths[$i];
        }

        $yy = $y - $headingH;
        foreach ($rows as $ri => $row) {
            if ($yy - $rowH < 60) {
                $this->text('Page break...', 8, false, 1, $yy - 4);
                $this->addPage();
                $yy = $this->pageH - 70;
                $this->headerBlock($yy);
                $yy -= 34;
            }
            if ($ri % 2 === 1) {
                $this->rect($x0, $yy - $rowH, $totalW, $rowH, '0.95 0.95 0.95');
            }
            $cx = $x0;
            foreach ($row as $ci => $val) {
                $this->cellText((string) $val, $cx + 4, $yy - 3.6, 9, false);
                $cx += $widths[$ci];
            }
            $yy -= $rowH;
        }
        return $yy;
    }

    public function output(string $filename = 'report.pdf'): never
    {
        $nPages = count($this->pages);
        $fontR = 3 + $nPages * 2;
        $fontB = $fontR + 1;

        $obj = function (string $body, int $id): string {
            return $id . " 0 obj\n" . $body . "\nendobj\n";
        };

        $kids = [];
        for ($i = 0; $i < $nPages; $i++) {
            $kids[] = (3 + $i * 2) . ' 0 R';
        }

        $pdf = '%PDF-1.4' . "\n";
        $offsets = [0];

        $append = function (string $chunk) use (&$pdf, &$offsets): void {
            $offsets[] = strlen($pdf);
            $pdf .= $chunk;
        };

        $append($obj('<< /Type /Catalog /Pages 2 0 R >>', 1));
        $append($obj('<< /Type /Pages /Kids [' . implode(' ', $kids) . '] /Count ' . $nPages . ' >>', 2));

        for ($i = 0; $i < $nPages; $i++) {
            $pageId = 3 + $i * 2;
            $contentId = $pageId + 1;
            $append($obj(sprintf(
                '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 %.1f %.1f] /Resources << /Font << /F1 %d 0 R /F2 %d 0 R >> >> /Contents %d 0 R >>',
                $this->pageW, $this->pageH, $fontR, $fontB, $contentId
            ), $pageId));
            $stream = $this->renderStream($this->pages[$i]);
            $deflated = gzdeflate($stream);
            if ($deflated !== false) {
                $body = '<< /Length ' . strlen($deflated) . ' /Filter /FlateDecode >> stream' . "\n" . $deflated . "\nendstream";
            } else {
                $body = '<< /Length ' . strlen($stream) . ' >> stream' . "\n" . $stream . "\nendstream";
            }
            $append($obj($body, $contentId));
        }

        $append($obj('<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>', $fontR));
        $append($obj('<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>', $fontB));

        $totalObjs = $fontB;
        $xrefPos = strlen($pdf);
        $pdf .= "xref\n0 " . ($totalObjs + 1) . "\n0000000000 65535 f \n";
        foreach ($offsets as $off) {
            $pdf .= sprintf("%010d 00000 n \n", $off);
        }
        $pdf .= "trailer\n<< /Size " . ($totalObjs + 1) . " /Root 1 0 R >>\nstartxref\n" . $xrefPos . "\n%%EOF\n";

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo $pdf;
        exit;
    }

    private function renderStream(array $commands): string
    {
        return implode("\n", $commands);
    }

    private function currentY(): float
    {
        return $this->pageH - 50;
    }

    private function clean(string $text): string
    {
        return html_entity_decode(strip_tags($text), ENT_QUOTES, 'UTF-8');
    }

    private function escape(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }

    private function strWidth(string $text, float $size): float
    {
        $w = 0;
        for ($i = 0, $len = strlen($text); $i < $len; $i++) {
            $w += $size * 0.5;
        }
        return $w;
    }
}