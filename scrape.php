<?php
function scrape_website($query) {
    // Scraping dari Bing
    $bingURL = "https://www.bing.com/search?q=" . urlencode($query);
    $bingResponse = file_get_contents($bingURL);
    $bingResult = extract_text_from_html($bingResponse, "bing");

    // Scraping dari GitHub
    $githubURL = "https://github.com/search?q=" . urlencode($query);
    $githubResponse = file_get_contents($githubURL);
    $githubResult = extract_text_from_html($githubResponse, "github");

    // Scraping dari semua subdomain Google (*.google.com)
    $googleSubdomainURL = "https://www.google.com/search?q=site:*.google.com " . urlencode($query);
    $googleResponse = file_get_contents($googleSubdomainURL);
    $googleResult = extract_text_from_html($googleResponse, "google");

    // Menggabungkan hasil dari semua sumber
    $finalResult = "Hasil Pencarian:\n\n";
    $finalResult .= "Bing:\n" . $bingResult . "\n\n";
    $finalResult .= "GitHub:\n" . $githubResult . "\n\n";
    $finalResult .= "Google Subdomains:\n" . $googleResult . "\n\n";

    return $finalResult;
}

// Fungsi untuk mengekstrak teks dari HTML, dapat disesuaikan untuk setiap sumber
function extract_text_from_html($html, $source) {
    if ($source == "bing") {
        // Contoh sederhana untuk mengekstrak hasil dari Bing
        if (preg_match('/<ol id="b_results">(.*?)<\/ol>/s', $html, $matches)) {
            return strip_tags($matches[1]);
        }
    } elseif ($source == "github") {
        // Contoh sederhana untuk mengekstrak hasil dari GitHub
        if (preg_match('/<div class="codesearch-results">(.*?)<\/div>/s', $html, $matches)) {
            return strip_tags($matches[1]);
        }
    } elseif ($source == "google") {
        // Contoh sederhana untuk mengekstrak hasil dari subdomain Google
        if (preg_match('/<div id="search">(.*?)<\/div>/s', $html, $matches)) {
            return strip_tags($matches[1]);
        }
    }

    // Default jika parsing gagal
    return "Tidak dapat mengekstrak hasil dari sumber: " . ucfirst($source);
}
?>