<?php
/**
 * Contact form handler for the static-exported Metwiser site.
 * Deployed alongside the static files (out/api/contact.php) and hit
 * directly by the client-side form via fetch().
 */

header('Content-Type: application/json');

// Change this to where submissions should land.
$recipient = 'hello@metwiser.com';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$raw = file_get_contents('php://input');
$input = json_decode($raw, true);
if (!is_array($input)) {
    $input = $_POST;
}

// Honeypot: real visitors never fill this hidden field. Bots that
// autofill every input will, so pretend success and drop it silently.
if (!empty($input['hp_field'])) {
    echo json_encode(['success' => true]);
    exit;
}

function field(array $input, string $key): string
{
    return isset($input[$key]) ? trim((string) $input[$key]) : '';
}

$name = field($input, 'name');
$company = field($input, 'company');
$email = field($input, 'email');
$interest = field($input, 'interest');
$message = field($input, 'message');

$errors = [];
if ($name === '') {
    $errors[] = 'Name is required.';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'A valid email is required.';
}
if ($message === '') {
    $errors[] = 'Message is required.';
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// Strip CR/LF so submitted values can't inject extra mail headers.
function clean_header(string $value): string
{
    return str_replace(["\r", "\n"], '', $value);
}

$interestLabels = [
    'sourcing' => 'Sourcing & Supplier Network',
    'manufacturing' => 'Manufacturing & Private Label',
    'rd' => 'R&D & Formulation',
    'quality' => 'Quality & Compliance',
    'packaging' => 'Packaging & Branding',
    'logistics' => 'Logistics & Fulfillment',
    'other' => 'Something else',
];
$interestLabel = $interestLabels[$interest] ?? $interest;

$subject = 'New contact form submission from ' . clean_header($name);

$body = "Name: {$name}\n";
$body .= "Company: " . ($company !== '' ? $company : '—') . "\n";
$body .= "Email: {$email}\n";
$body .= "Looking for: " . ($interestLabel !== '' ? $interestLabel : '—') . "\n\n";
$body .= "Message:\n{$message}\n";

$domain = clean_header($_SERVER['SERVER_NAME'] ?? 'metwiser.com');

$headers = [];
$headers[] = 'From: Metwiser Website <no-reply@' . $domain . '>';
$headers[] = 'Reply-To: ' . clean_header($email);
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = 'X-Mailer: PHP/' . phpversion();

$sent = mail($recipient, $subject, $body, implode("\r\n", $headers));

if ($sent) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Unable to send your message right now. Please try again later or email us directly.',
    ]);
}
