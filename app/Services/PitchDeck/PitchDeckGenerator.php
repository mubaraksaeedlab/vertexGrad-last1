<?php

namespace App\Services\PitchDeck;

use App\Models\Project;
use App\Models\ProjectPitchDeck;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpPresentation\DocumentLayout;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\Shape\AutoShape;
use PhpOffice\PhpPresentation\Shape\Drawing\File as DrawingFile;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Fill;

class PitchDeckGenerator
{
    // Design canvas: true 16:9. Every element is positioned against this grid.
    protected int $baseSlideWidth = 1280;
    protected int $baseSlideHeight = 720;

    // Output canvas: keep the same 16:9 ratio to prevent stretching/compression.
    protected int $slideWidth = 1280;
    protected int $slideHeight = 720;

    protected string $white = 'FFFFFFFF';
    protected string $textOnDark = 'FFE5E7EB';
    protected string $mutedOnDark = 'FF94A3B8';

    protected string $dark = 'FF071224';
    protected string $dark2 = 'FF0B1730';
    protected string $dark3 = 'FF0F1D3A';
    protected string $dark4 = 'FF132544';
    protected string $lineDark = 'FF223556';

    protected string $accent = 'FF2563EB';
    protected string $accentDark = 'FF1D4ED8';
    protected string $accentSoft = 'FFDBEAFE';

    protected string $green = 'FF16A34A';
    protected string $greenSoft = 'FFDCFCE7';

    protected string $orange = 'FFEA580C';
    protected string $orangeSoft = 'FFFFEDD5';

    protected string $violet = 'FF7C3AED';
    protected string $violetSoft = 'FFEDE9FE';

    public function __construct(
        protected PitchDeckDataBuilder $dataBuilder
    ) {
    }

    public function generate(Project $project, ?int $generatedBy = null): ProjectPitchDeck
    {
        $data = $this->dataBuilder->build($project);

        $deck = ProjectPitchDeck::create([
            'project_id' => $project->project_id,
            'version' => ((int) ProjectPitchDeck::where('project_id', $project->project_id)->max('version')) + 1,
            'status' => 'pending',
            'generated_by' => $generatedBy,
        ]);

        try {
            $presentation = new PhpPresentation();

            // Standard PowerPoint widescreen slide: 16:9.
            // Do not force CX/CY manually here; PhpPresentation writes a valid 16:9 layout.
            $presentation->getLayout()->setDocumentLayout(DocumentLayout::LAYOUT_SCREEN_16X9);

            $presentation->removeSlideByIndex(0);

            $this->coverSlide($presentation, $project, $data);
            $this->overviewSlide($presentation, $data);
            $this->problemImpactSlide($presentation, $data);
            $this->fundingSlide($presentation, $data);
            $this->milestonesSlide($presentation, $data);
            $this->readinessSlide($presentation, $data);

            $directory = storage_path('app/private/pitch-decks');
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $fileName = 'project_' . $project->project_id . '_v' . $deck->version . '.pptx';
            $fullPath = $directory . DIRECTORY_SEPARATOR . $fileName;
            $relativePath = 'private/pitch-decks/' . $fileName;

            $writer = IOFactory::createWriter($presentation, 'PowerPoint2007');
            $writer->save($fullPath);

            $deck->update([
                'pptx_path' => $relativePath,
                'status' => 'generated',
                'generated_at' => now(),
                'generation_error' => null,
            ]);

            return $deck;
        } catch (\Throwable $e) {
            $deck->update([
                'status' => 'failed',
                'generation_error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    protected function coverSlide(PhpPresentation $presentation, Project $project, array $data): void
    {
        $slide = $presentation->createSlide();

        $this->shape($slide, 0, 0, $this->baseSlideWidth, $this->baseSlideHeight, $this->dark, $this->dark);
        $this->shape($slide, 0, 0, $this->baseSlideWidth, 18, $this->accent, $this->accent);
        $this->shape($slide, 0, 560, $this->baseSlideWidth, 160, 'FF09182F', 'FF09182F');
        $this->text($slide, 72, 52, 340, 20, 'VERTEXGRAD · INVESTOR PITCH DECK', 13, true, 'FFBFDBFE');

        $this->text(
            $slide,
            72,
            96,
            700,
            84,
            $this->limitText($this->display($data['title'], 'Project Pitch Deck'), 78),
            31,
            true,
            $this->white
        );

        $this->text(
            $slide,
            72,
            188,
            650,
            84,
            $this->limitText(
                $this->display(
                    $data['description'],
                    'A concise investor-facing presentation generated from approved project information.'
                ),
                220
            ),
            18,
            false,
            $this->textOnDark
        );

        $this->coverIdentityBlock(
            $slide,
            72,
            302,
            650,
            120,
            $this->display($data['category']),
            $this->display($data['project_type']),
            $this->display($data['project_nature'])
        );

        $imageX = 852;
        $imageY = 70;
        $imageW = 348;
        $imageH = 430;

        $this->shape($slide, $imageX, $imageY, $imageW, $imageH, $this->dark3, $this->dark3);
        $this->shape($slide, $imageX + 16, $imageY + 16, $imageW - 32, $imageH - 32, $this->dark4, $this->dark4);

        $imagePath = $this->resolveProjectImagePath($project);
        if ($imagePath) {
            $shape = new DrawingFile();
            $shape->setPath($imagePath)
                ->setWidth($this->scaleX($imageW - 32))
                ->setHeight($this->scaleY($imageH - 32))
                ->setOffsetX($this->scaleX($imageX + 16))
                ->setOffsetY($this->scaleY($imageY + 16));
            $slide->addShape($shape);
        } else {
            $this->text($slide, $imageX + 40, $imageY + 160, $imageW - 80, 40, 'VertexGrad', 29, true, 'FF93C5FD', Alignment::HORIZONTAL_CENTER);
            $this->text($slide, $imageX + 40, $imageY + 204, $imageW - 80, 24, 'Investor Presentation', 15, false, $this->white, Alignment::HORIZONTAL_CENTER);
        }

        $metricY = 592;
        $gap = 22;

        $this->coverMetric($slide, 72, $metricY, 250, 82, 'Budget', $this->money($data['budget']));
        $this->coverMetric($slide, 72 + 250 + $gap, $metricY, 225, 82, 'Duration', $this->formatDuration($data['duration_months']));
        $this->coverMetric($slide, 72 + 250 + $gap + 225 + $gap, $metricY, 250, 82, 'Support Type', $this->limitText($this->display($data['support_type']), 26));
        $this->coverMetric($slide, 72 + 250 + $gap + 225 + $gap + 250 + $gap, $metricY, 395, 82, 'Institution', $this->limitText($this->display($data['university_name']), 42));

        $this->text($slide, 72, 690, 1130, 14, 'Prepared for investor review and opportunity screening.', 10, false, 'FFCBD5E1', Alignment::HORIZONTAL_RIGHT);
    }

    protected function overviewSlide(PhpPresentation $presentation, array $data): void
    {
        $slide = $presentation->createSlide();
        $this->pageBase($slide, 'Executive Overview', 'A concise view of the project, its context, and its intended value.');

        $this->darkPanel(
            $slide,
            70,
            138,
            730,
            220,
            'Project Summary',
            $this->limitText($this->display($data['description'], 'No summary provided.'), 420)
        );

        $this->softDarkStat($slide, 830, 146, 360, 62, 'Funding Needed', $this->display($data['needs_funding'], 'Not specified'), 'FF102345', 'FF93C5FD');
        $this->softDarkStat($slide, 830, 222, 360, 62, 'Scanner Status', $this->display($data['scanner_status'], 'Not available'), 'FF102A20', 'FF86EFAC');
        $this->softDarkStat($slide, 830, 298, 360, 62, 'Department', $this->display($data['department'], 'Not specified'), 'FF23173D', 'FFD8B4FE');

        $this->darkFocusCard($slide, 70, 396, 360, 210, 'Project Nature', $this->display($data['project_nature'], 'Not specified'));
        $this->darkFocusCard($slide, 460, 396, 360, 210, 'Project Type', $this->display($data['project_type'], 'Not specified'));
        $this->darkFocusCard($slide, 850, 396, 340, 210, 'Target Beneficiaries', $this->limitText($this->display($data['target_beneficiaries'], 'Not specified'), 110));
    }

    protected function problemImpactSlide(PhpPresentation $presentation, array $data): void
    {
        $slide = $presentation->createSlide();
        $this->pageBase($slide, 'Problem & Impact', 'Why this project matters and what value it aims to create.');

        $this->darkPanel(
            $slide,
            70,
            142,
            550,
            236,
            'Problem Statement',
            $this->limitText($this->display($data['problem_statement'], 'Problem statement not provided.'), 280)
        );

        $this->darkPanel(
            $slide,
            660,
            142,
            550,
            236,
            'Expected Impact',
            $this->limitText($this->display($data['expected_impact'], 'Expected impact not provided.'), 280)
        );

        $this->shape($slide, 70, 410, 1140, 214, $this->dark3, $this->dark3);
        $this->shape($slide, 70, 410, 1140, 8, $this->accent, $this->accent);

        $this->text($slide, 96, 442, 300, 24, 'Community Benefit', 18, true, $this->white);
        $this->text(
            $slide,
            96,
            482,
            500,
            100,
            $this->limitText($this->display($data['community_benefit'], 'Community benefit not provided.'), 230),
            17,
            false,
            $this->textOnDark
        );

        $this->impactBadge($slide, 660, 454, 165, 98, 'Category', $this->limitText($this->display($data['category']), 18));
        $this->impactBadge($slide, 845, 454, 165, 98, 'Beneficiaries', $this->summarizeBeneficiaries($data['target_beneficiaries']));
        $this->impactBadge($slide, 1030, 454, 150, 98, 'Region', $this->limitText($this->display($data['governorate']), 16));
    }

    protected function fundingSlide(PhpPresentation $presentation, array $data): void
    {
        $slide = $presentation->createSlide();
        $this->pageBase($slide, 'Funding Overview', 'Capital need, timeline, and requested support structure.');

        $this->darkMetricCard($slide, 70, 140, 265, 108, 'Budget', $this->money($data['budget']), 'FF102A20', 'FF86EFAC');
        $this->darkMetricCard($slide, 355, 140, 250, 108, 'Duration', $this->formatDuration($data['duration_months']), 'FF102345', 'FF93C5FD');
        $this->darkMetricCard($slide, 625, 140, 280, 108, 'Needs Funding', $this->display($data['needs_funding'], 'Not specified'), 'FF311A0C', 'FFFDBA74');
        $this->darkMetricCard($slide, 925, 140, 285, 108, 'Support Type', $this->limitText($this->display($data['support_type'], 'Not specified'), 26), 'FF23173D', 'FFD8B4FE');

        $this->darkPanel(
            $slide,
            70,
            285,
            720,
            315,
            'Budget Breakdown',
            $this->limitText($this->display($data['budget_breakdown'], 'Budget breakdown not provided.'), 600)
        );

        $this->shape($slide, 820, 285, 390, 315, 'FF081B36', 'FF081B36');
        $this->shape($slide, 820, 285, 390, 8, $this->green, $this->green);

        $this->text($slide, 850, 318, 280, 22, 'Funding Position', 18, true, $this->white);
        $this->text($slide, 850, 356, 300, 56, $this->money($data['budget']), 31, true, 'FFBBF7D0');
        $this->text($slide, 850, 420, 290, 18, 'Target capital requirement', 11, false, $this->mutedOnDark);

        $this->shape($slide, 850, 458, 330, 1, 'FF334155', 'FF334155');

        $this->text($slide, 850, 482, 160, 16, 'Funding Needed', 11, true, 'FF93C5FD');
        $this->text($slide, 850, 502, 300, 24, $this->limitText($this->display($data['needs_funding'], 'Not specified'), 28), 18, true, $this->white);

        $this->text($slide, 850, 538, 160, 16, 'Support Type', 11, true, 'FFFFD6AE');
        $this->text($slide, 850, 558, 300, 24, $this->limitText($this->display($data['support_type'], 'Not specified'), 28), 18, true, $this->white);
    }

    protected function milestonesSlide(PhpPresentation $presentation, array $data): void
    {
        $slide = $presentation->createSlide();
        $this->pageBase($slide, 'Execution Milestones', 'Key phases planned for implementation and delivery.');

        $milestones = array_values($data['milestones'] ?? []);
        $milestones = array_pad($milestones, 3, null);

        $this->shape($slide, 110, 322, 1060, 3, $this->lineDark, $this->lineDark);

        foreach ($milestones as $i => $milestone) {
            $cardX = 70 + ($i * 390);

            $title = $this->display($milestone['title'] ?? null, 'Milestone details not provided');
            $month = $this->display($milestone['month'] ?? null, 'TBD');

            $this->shape($slide, $cardX, 164, 350, 350, $this->dark3, $this->dark3);
            $this->shape($slide, $cardX, 164, 350, 8, $this->accent, $this->accent);

            $this->text($slide, $cardX + 28, 196, 70, 26, '0' . ($i + 1), 26, true, 'FF60A5FA');
            $this->text($slide, $cardX + 28, 228, 150, 18, 'Milestone', 11, true, $this->mutedOnDark);

            $this->text(
                $slide,
                $cardX + 28,
                270,
                294,
                96,
                $this->limitText($title, 82),
                20,
                true,
                $this->white
            );

            $this->shape($slide, $cardX + 164, 304, 18, 18, $this->accent, $this->accent);

            $this->shape($slide, $cardX + 28, 410, 294, 1, $this->lineDark, $this->lineDark);
            $this->text($slide, $cardX + 28, 432, 120, 18, 'Planned Month', 11, true, $this->mutedOnDark);
            $this->text($slide, $cardX + 28, 456, 160, 28, $month, 23, true, 'FF93C5FD');
        }
    }

    protected function readinessSlide(PhpPresentation $presentation, array $data): void
    {
        $slide = $presentation->createSlide();
        $this->pageBase($slide, 'Academic & Review Readiness', 'Institutional context, review status, and credibility indicators.');

        $this->darkPanel(
            $slide,
            70,
            142,
            540,
            215,
            'Academic Context',
            $this->buildAcademicContext($data)
        );

        $this->darkPanel(
            $slide,
            640,
            142,
            570,
            215,
            'Final Review Note',
            $this->limitText($this->display($data['final_notes'], 'No final review note available.'), 320)
        );

        $this->darkMetricCard($slide, 70, 390, 255, 112, 'Approved Reviews', (string) ($data['approved_reviews_count'] ?? 0), 'FF102345', 'FF93C5FD');
        $this->darkMetricCard($slide, 345, 390, 255, 112, 'Approved Investments', (string) ($data['approved_investments_count'] ?? 0), 'FF102A20', 'FF86EFAC');
        $this->darkMetricCard($slide, 620, 390, 255, 112, 'Scan Score', $this->display($data['scan_score'], 'Not available'), 'FF23173D', 'FFD8B4FE');
        $this->darkMetricCard($slide, 895, 390, 315, 112, 'Scanner Status', $this->limitText($this->display($data['scanner_status'], 'Not available'), 28), 'FF311A0C', 'FFFDBA74');

        $this->shape($slide, 70, 530, 1140, 92, 'FF081B36', 'FF081B36');
        $this->shape($slide, 70, 530, 1140, 8, $this->accent, $this->accent);

        $this->text($slide, 96, 556, 220, 16, 'Decision Status', 11, true, 'FF93C5FD');
        $this->text($slide, 96, 576, 250, 24, $this->limitText($this->display($data['final_decision'], 'Pending'), 24), 20, true, $this->white);

        $this->text($slide, 390, 556, 200, 16, 'Supervisor', 11, true, 'FF93C5FD');
        $this->text($slide, 390, 576, 260, 24, $this->limitText($this->display($data['supervisor_name']), 28), 19, true, $this->white);

        $this->text($slide, 700, 556, 180, 16, 'Student', 11, true, 'FF93C5FD');
        $this->text($slide, 700, 576, 240, 24, $this->limitText($this->display($data['student_name']), 28), 19, true, $this->white);

        $this->text($slide, 980, 556, 170, 16, 'Reviewed At', 11, true, 'FF93C5FD');
        $this->text(
            $slide,
            980,
            576,
            170,
            24,
            $this->limitText($this->display($data['final_decided_at'], 'Not recorded'), 20),
            16,
            true,
            $this->white,
            Alignment::HORIZONTAL_RIGHT
        );
    }

    protected function pageBase($slide, string $title, string $subtitle): void
    {
        $this->shape($slide, 0, 0, $this->baseSlideWidth, $this->baseSlideHeight, $this->dark, $this->dark);
        $this->shape($slide, 0, 0, $this->baseSlideWidth, 74, 'FF06101F', 'FF06101F');

        $this->text($slide, 70, 18, 760, 28, $title, 24, true, $this->white);
        $this->text($slide, 70, 94, 860, 20, $subtitle, 13, false, $this->mutedOnDark);
        $this->shape($slide, 70, 118, 150, 5, $this->accent, $this->accent);
    }

    protected function coverIdentityBlock($slide, int $x, int $y, int $w, int $h, string $category, string $type, string $nature): void
    {
        $this->shape($slide, $x, $y, $w, $h, $this->dark3, $this->dark3);

        $colW = (int) floor($w / 3);

        $this->coverMetaColumn($slide, $x + 22, $y + 22, $colW - 26, 'Category', $category);
        $this->coverMetaColumn($slide, $x + $colW + 10, $y + 22, $colW - 26, 'Project Type', $type);
        $this->coverMetaColumn($slide, $x + ($colW * 2), $y + 22, $colW - 20, 'Project Nature', $nature);
    }

    protected function coverMetaColumn($slide, int $x, int $y, int $w, string $label, string $value): void
    {
        $this->text($slide, $x, $y, $w, 16, strtoupper($label), 10, true, 'FF93C5FD');
        $this->shape($slide, $x, $y + 24, 56, 3, $this->accent, $this->accent);
        $this->text($slide, $x, $y + 40, $w, 42, $this->limitText($value, 28), 17, true, $this->white);
    }

    protected function coverMetric($slide, int $x, int $y, int $w, int $h, string $label, string $value): void
    {
        $this->shape($slide, $x, $y, $w, $h, $this->dark4, $this->dark4);
        $this->text($slide, $x + 16, $y + 14, $w - 32, 14, $label, 10, true, 'FF93C5FD');
        $this->text($slide, $x + 16, $y + 38, $w - 32, 26, $value, 17, true, $this->white);
    }

    protected function darkPanel($slide, int $x, int $y, int $w, int $h, string $label, string $body): void
    {
        $this->shape($slide, $x, $y, $w, $h, $this->dark3, $this->dark3);
        $this->shape($slide, $x, $y, 8, $h, $this->accent, $this->accent);

        $this->text($slide, $x + 26, $y + 18, $w - 52, 24, $label, 18, true, $this->white);
        $this->text($slide, $x + 26, $y + 58, $w - 52, $h - 82, $body, 17, false, $this->textOnDark);
    }

    protected function softDarkStat($slide, int $x, int $y, int $w, int $h, string $label, string $value, string $bg, string $labelColor): void
    {
        $this->shape($slide, $x, $y, $w, $h, $bg, $bg);
        $this->text($slide, $x + 18, $y + 12, $w - 36, 12, $label, 10, true, $labelColor);
        $this->text($slide, $x + 18, $y + 30, $w - 36, 20, $this->limitText($value, 34), 15, true, $this->white);
    }

    protected function darkFocusCard($slide, int $x, int $y, int $w, int $h, string $label, string $value): void
    {
        $this->shape($slide, $x, $y, $w, $h, $this->dark3, $this->dark3);
        $this->shape($slide, $x, $y, $w, 7, $this->accent, $this->accent);

        $this->text($slide, $x + 24, $y + 28, $w - 48, 20, $label, 16, true, $this->textOnDark);
        $this->shape($slide, $x + 24, $y + 58, 74, 3, 'FF60A5FA', 'FF60A5FA');
        $this->text($slide, $x + 24, $y + 86, $w - 48, $h - 108, $value, 18, false, $this->white);
    }

    protected function impactBadge($slide, int $x, int $y, int $w, int $h, string $label, string $value): void
    {
        $this->shape($slide, $x, $y, $w, $h, $this->dark4, $this->dark4);
        $this->text($slide, $x + 14, $y + 14, $w - 28, 12, $label, 10, true, 'FF93C5FD');
        $this->text($slide, $x + 14, $y + 40, $w - 28, 32, $this->limitText($value, 18), 16, true, $this->white, Alignment::HORIZONTAL_CENTER);
    }

    protected function darkMetricCard($slide, int $x, int $y, int $w, int $h, string $label, string $value, string $bg, string $labelColor): void
    {
        $this->shape($slide, $x, $y, $w, $h, $bg, $bg);
        $this->text($slide, $x + 16, $y + 14, $w - 32, 14, $label, 11, true, $labelColor);
        $this->text($slide, $x + 16, $y + 44, $w - 32, 28, $this->limitText($value, 32), 19, true, $this->white);
    }

    protected function buildAcademicContext(array $data): string
    {
        $parts = [
            'Student: ' . $this->display($data['student_name']),
            'Academic Level: ' . $this->display($data['academic_level'], 'Not specified'),
            'Supervisor: ' . $this->display($data['supervisor_name']),
            'University: ' . $this->display($data['university_name'], 'Not specified'),
            'Department: ' . $this->display($data['department'], 'Not specified'),
            'Governorate: ' . $this->display($data['governorate'], 'Not specified'),
        ];

        return implode("\n", $parts);
    }

    protected function formatDuration($months): string
    {
        if (!$months || !is_numeric($months)) {
            return 'Not specified';
        }

        $months = (int) $months;

        return $months . ' ' . ($months === 1 ? 'Month' : 'Months');
    }

    protected function summarizeBeneficiaries(?string $text): string
    {
        $text = trim((string) $text);

        if ($text === '') {
            return 'Not specified';
        }

        if (mb_strlen($text) <= 18) {
            return $text;
        }

        return 'Defined';
    }

    protected function display($value, string $fallback = 'Not specified'): string
    {
        $value = trim((string) $value);

        return $value === '' || $value === '-' ? $fallback : $value;
    }

    protected function shape($slide, int $x, int $y, int $w, int $h, string $fill, string $border, bool $scale = true): void
    {
        $finalX = $this->scaleX($x);
        $finalY = $this->scaleY($y);
        $finalW = $this->scaleX($w);
        $finalH = $this->scaleY($h);
        $shape = new AutoShape();
        $shape->setType(AutoShape::TYPE_RECTANGLE)
            ->setOffsetX($finalX)
            ->setOffsetY($finalY)
            ->setWidth($finalW)
            ->setHeight($finalH);

        $shape->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new Color($fill));
        $shape->getBorder()->setColor(new Color($border));
        $slide->addShape($shape);
    }

    protected function text(
        $slide,
        int $x,
        int $y,
        int $w,
        int $h,
        string $text,
        int $size,
        bool $bold,
        string $color,
        string $align = Alignment::HORIZONTAL_LEFT
    ): void {
        $shape = $slide->createRichTextShape()
            ->setOffsetX($this->scaleX($x))
            ->setOffsetY($this->scaleY($y))
            ->setWidth($this->scaleX($w))
            ->setHeight($this->scaleY($h));

        $shape->getActiveParagraph()->getAlignment()->setHorizontal($align);

        $run = $shape->createTextRun($text);
        $run->getFont()
            ->setName('Arial')
            ->setSize($this->scaleFont($size))
            ->setBold($bold)
            ->setColor(new Color($color));
    }

    protected function resolveProjectImagePath(Project $project): ?string
    {
        try {
            $media = $project->getFirstMedia('images');

            if (!$media || !file_exists($media->getPath())) {
                return null;
            }

            $path = $media->getPath();
            $imageInfo = @getimagesize($path);

            if (!$imageInfo || empty($imageInfo['mime'])) {
                return null;
            }

            // PowerPoint repair warnings usually happen when an unsupported image
            // type is embedded. Keep only formats PowerPoint opens cleanly.
            $allowedMimes = [
                'image/jpeg',
                'image/png',
                'image/gif',
            ];

            return in_array($imageInfo['mime'], $allowedMimes, true) ? $path : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function money($value): string
    {
        if (!is_numeric($value)) {
            return 'Not specified';
        }

        return '$' . number_format((float) $value, 0);
    }

    protected function limitText(?string $text, int $limit = 220): string
    {
        $text = trim((string) $text);

        if ($text === '') {
            return 'Not specified';
        }

        return mb_strlen($text) > $limit
            ? mb_substr($text, 0, $limit - 3) . '...'
            : $text;
    }

    protected function scaleX(float|int $value): int
    {
        return (int) round(($value / $this->baseSlideWidth) * $this->slideWidth);
    }

    protected function scaleY(float|int $value): int
    {
        return (int) round(($value / $this->baseSlideHeight) * $this->slideHeight);
    }

    protected function scaleFont(float|int $value): int
    {
        $ratio = $this->slideWidth / $this->baseSlideWidth;

        return max(1, (int) round($value * $ratio));
    }
}