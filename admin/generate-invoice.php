<?php
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$invoice_id = $_GET['invoice_id'] ?? null) {
   // Normal flow
}

$invoice = null;
if ($invoice_id) {
    $stmt = $pdo->prepare("
        SELECT i.*, c.name as client_name, c.email as client_email, c.phone as client_phone, c.website as client_website
        FROM client_invoices i 
        JOIN clients c ON i.client_id = c.id 
        WHERE i.id = ?
    ");
    $stmt->execute([$invoice_id]);
    $invoice = $stmt->fetch();
}

if (!$invoice) die("Invoice not found");

// Company Details (You can make this dynamic later)
$company = [
    'name' => 'Travel Bee Agency',
    'address' => '123 Creative Studio, Digital Valley',
    'email' => 'billing@travelbee.com',
    'phone' => '+1 234 567 8900'
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?php echo $invoice['invoice_ref']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-print-color-adjust: exact; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; background: white; }
            .print-container { box-shadow: none; padding: 0; margin: 0; max-width: 100%; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <div class="max-w-4xl mx-auto mb-6 flex justify-between items-center no-print">
        <a href="client-details.php?id=<?php echo $invoice['client_id']; ?>&tab=billing" class="text-gray-500 hover:text-gray-900 font-medium">← Back to Client</a>
        <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold shadow-lg hover:bg-blue-700 transition">Download / Print PDF</button>
    </div>

    <div class="print-container bg-white p-12 max-w-4xl mx-auto rounded-xl shadow-sm">
        
        <!-- Header -->
        <div class="flex justify-between items-start mb-12">
            <div>
                <div class="flex items-center space-x-2 text-orange-500 mb-4">
                    <span class="text-4xl">🐝</span>
                    <span class="text-2xl font-bold text-gray-900 tracking-tight">Travel Bee</span>
                </div>
                <div class="text-gray-500 text-sm leading-relaxed">
                    <?php echo $company['address']; ?><br>
                    <?php echo $company['email']; ?><br>
                    <?php echo $company['phone']; ?>
                </div>
            </div>
            <div class="text-right">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-2">INVOICE</h1>
                <p class="text-gray-500 font-medium text-lg">#<?php echo $invoice['invoice_ref']; ?></p>
                
                <div class="mt-4 inline-block text-left bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <div class="flex justify-between gap-8 mb-1">
                        <span class="text-gray-500 text-xs uppercase font-bold">Issue Date:</span>
                        <span class="font-bold text-gray-900 text-sm"><?php echo date('M d, Y'); ?></span>
                    </div>
                    <div class="flex justify-between gap-8">
                        <span class="text-gray-500 text-xs uppercase font-bold">Due Date:</span>
                        <span class="font-bold text-gray-900 text-sm"><?php echo date('M d, Y', strtotime($invoice['due_date'])); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bill To -->
        <div class="border-t border-b border-gray-100 py-8 mb-12 flex justify-between">
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Bill To</h3>
                <h2 class="text-xl font-bold text-gray-900 mb-1"><?php echo htmlspecialchars($invoice['client_name']); ?></h2>
                <div class="text-gray-500 text-sm">
                    <?php echo htmlspecialchars($invoice['client_email']); ?><br>
                    <?php echo htmlspecialchars($invoice['client_phone']); ?><br>
                    <?php if(!empty($invoice['client_website'])): ?>
                    <a href="#" class="text-blue-500 underline"><?php echo htmlspecialchars($invoice['client_website']); ?></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="text-right">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Total Amount</h3>
                <h2 class="text-4xl font-bold text-gray-900 text-orange-600">₹<?php echo number_format($invoice['amount']); ?></h2>
                <div class="mt-2">
                     <span class="px-3 py-1 rounded-full text-xs font-bold uppercase <?php echo $invoice['status']=='paid'?'bg-green-100 text-green-700':($invoice['status']=='overdue'?'bg-red-100 text-red-700':'bg-yellow-100 text-yellow-700'); ?>">
                        <?php echo $invoice['status']; ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Line Items -->
        <table class="w-full mb-12">
            <thead>
                <tr class="border-b-2 border-gray-900">
                    <th class="text-left py-4 text-xs font-bold text-gray-900 uppercase tracking-wider">Description</th>
                    <th class="text-right py-4 text-xs font-bold text-gray-900 uppercase tracking-wider w-32">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="py-6">
                        <p class="font-bold text-gray-900 text-lg mb-1 capitalize"><?php echo $invoice['description'] ? htmlspecialchars($invoice['description']) : 'Digital Marketing Services'; ?></p>
                        <p class="text-gray-500 text-sm">Professional services rendered as per agreement.</p>
                    </td>
                    <td class="py-6 text-right font-bold text-gray-900 text-lg">
                        ₹<?php echo number_format($invoice['amount']); ?>
                    </td>
                </tr>
                <!-- Spacer for visual balance if needed -->
                <tr><td class="py-8" colspan="2"></td></tr>
            </tbody>
            <tfoot class="border-t-2 border-gray-900">
                <tr>
                    <td class="pt-6 text-right font-medium text-gray-500">Subtotal</td>
                    <td class="pt-6 text-right font-bold text-gray-900">₹<?php echo number_format($invoice['amount']); ?></td>
                </tr>
                <tr>
                    <td class="pt-2 text-right font-medium text-gray-500">Tax (0%)</td>
                    <td class="pt-2 text-right font-bold text-gray-900">₹0.00</td>
                </tr>
                 <tr>
                    <td class="pt-4 text-right font-bold text-xl text-gray-900">Total Due</td>
                    <td class="pt-4 text-right font-bold text-xl text-orange-600">₹<?php echo number_format($invoice['amount']); ?></td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="bg-gray-50 rounded-xl p-6 text-sm text-gray-500 flex justify-between items-center">
            <div>
                <p class="font-bold text-gray-900 mb-1">Payment Details</p>
                <p>Bank: HDFC Bank</p>
                <p>Account: 1234567890</p>
                <p>IFSC: HDFC0001234</p>
                <p class="mt-1">Mode: <?php echo $invoice['mode'] ? $invoice['mode'] : 'Bank Transfer'; ?></p>
            </div>
            <div class="text-right">
                <p class="font-bold text-gray-900 mb-1">Thank you for your business!</p>
                <p>For questions, contact support@travelbee.com</p>
            </div>
        </div>

    </div>

</body>
</html>
