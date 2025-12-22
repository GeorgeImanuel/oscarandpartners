<?php

/**
 * Render due date with status indication
 * 
 * Displays the due date with color-coded status text indicating whether
 * the date is upcoming, today, or overdue.
 * 
 * @param string $due_date The due date to render (any format parseable by Carbon)
 * @return void Echoes the formatted HTML output
 * 
 * @example
 * renderDueDate('2025-11-15');
 * // Output: <strong class="text-success">Tersisa 15 hari lagi</strong><br>15 Nov 2025
 */
if(! function_exists('renderDueDate')){
	function renderDueDate($due_date)
	{ 
        // Calculate days difference (positive = overdue, negative = upcoming)
        $due_day     = floor(\Carbon\Carbon::parse($due_date)->diffInDays(now(), false));
        
        // Set status class: danger for overdue/today, success for upcoming
        $statusClass = $due_day >= 0 ? 'text-danger' : 'text-success';
        
        // Generate status text based on days remaining
        $statusText  = $due_day < 0
            ? 'Tersisa ' . (($due_day - 1) * -1) . ' hari lagi'  // Upcoming
            : ($due_day == 0 ? 'Hari ini' : 'Terlambat ' . $due_day . ' Hari');  // Today or overdue
        
        // Format the date for display
        $dueDateFormatted = \Carbon\Carbon::parse($due_date)->format('d M Y');
        
        // Output formatted HTML
        echo "<strong class=\"{$statusClass}\">{$statusText}</strong><br>{$dueDateFormatted}";
	}
}

/**
 * Format number with Indonesian formatting
 * 
 * Formats a number with thousand separators and optional Rupiah currency prefix.
 * Uses Indonesian number format (comma for decimals, dot for thousands).
 * 
 * @param int|float $number The number to format
 * @param bool $currency Whether to add 'Rp' prefix (default: true)
 * @param int $decimals Number of decimal places (default: 0)
 * @return string The formatted number string
 * 
 * @example
 * numberFormat(1000000);           // Returns: "Rp1.000.000"
 * numberFormat(1000000, false);    // Returns: "1.000.000"
 * numberFormat(1234.56, true, 2);  // Returns: "Rp1.234,56"
 */
if(! function_exists('numberFormat')){
	function numberFormat($number, $currency = true, $decimals = 0)
    { 
        if (!$currency) {
            return number_format($number, $decimals, ',', '.');
        }   
        return 'Rp' . number_format($number, $decimals, ',', '.');
    }
}