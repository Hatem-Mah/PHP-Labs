<!DOCTYPE html>
<html>
<head>
    <title>Delete Registration</title>
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .confirmation-card {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .record-preview {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
        
        .detail-row {
            display: flex;
            margin: 8px 0;
        }
        
        .detail-label {
            font-weight: bold;
            width: 120px;
            color: #333;
        }
        
        .detail-value {
            flex: 1;
            color: #666;
        }
        
        .nav-links {
            margin: 20px 0;
        }
        
        .nav-links a, .btn {
            margin-right: 15px;
            padding: 10px 15px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        
        .back-btn {
            background-color: #6c757d;
        }
        
        .cancel-btn {
            background-color: #28a745;
        }
        
        .delete-btn {
            background-color: #dc3545;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        
        .warning {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Delete Registration</h2>

        <?php
        require_once 'config/database.php';
        
        $id = $_GET['id'] ?? '';
        $confirm = $_POST['confirm'] ?? '';
        
        if (empty($id) || !is_numeric($id)) {
            echo "<div class='error'>Invalid ID provided.</div>";
            echo "<div class='nav-links'>";
            echo "<a href='list_data.php' class='back-btn'>← Back to All Registrations</a>";
            echo "</div>";
            exit;
        }
        
        try {
            // Get record from database
            $stmt = $pdo->prepare("SELECT * FROM registrations WHERE id = ?");
            $stmt->execute([$id]);
            $record = $stmt->fetch();
            
            if (!$record) {
                echo "<div class='error'>Record not found.</div>";
                echo "<div class='nav-links'>";
                echo "<a href='list_data.php' class='back-btn'>← Back to All Registrations</a>";
                echo "</div>";
                exit;
            }
            
            // If delete is confirmed, perform the deletion
            if ($confirm === 'yes') {
                // Delete the record from database
                $deleteStmt = $pdo->prepare("DELETE FROM registrations WHERE id = ?");
                
                if ($deleteStmt->execute([$id])) {
                    echo "<div class='success'>";
                    echo "<h3>✓ Record Deleted Successfully</h3>";
                    echo "<p>The registration for <strong>" . htmlspecialchars($record['fname'] . ' ' . $record['lname']) . "</strong> has been permanently deleted from the database.</p>";
                    echo "</div>";
                    
                    echo "<div class='nav-links'>";
                    echo "<a href='list_data.php' class='back-btn'>← Back to All Registrations</a>";
                    echo "<a href='registration.html' class='cancel-btn'>New Registration</a>";
                    echo "</div>";
                } else {
                    echo "<div class='error'>";
                    echo "<h3>✗ Error</h3>";
                    echo "<p>Failed to delete the record from database. Please try again.</p>";
                    echo "</div>";
                    
                    echo "<div class='nav-links'>";
                    echo "<a href='list_data.php' class='back-btn'>← Back to All Registrations</a>";
                    echo "</div>";
                }
            } else {
            // Show confirmation form
            echo "<div class='warning'>";
            echo "<h3>⚠ Confirm Deletion</h3>";
            echo "<p>Are you sure you want to <strong>permanently delete</strong> this registration? This action cannot be undone.</p>";
            echo "</div>";
            
            echo "<div class='record-preview'>";
            echo "<h4>Record to be deleted:</h4>";
            
            echo "<div class='detail-row'>";
            echo "<div class='detail-label'>Name:</div>";
            echo "<div class='detail-value'>" . htmlspecialchars($record['fname'] . ' ' . $record['lname']) . "</div>";
            echo "</div>";
            
            echo "<div class='detail-row'>";
            echo "<div class='detail-label'>Username:</div>";
            echo "<div class='detail-value'>" . htmlspecialchars($record['username']) . "</div>";
            echo "</div>";
            
            echo "<div class='detail-row'>";
            echo "<div class='detail-label'>Address:</div>";
            echo "<div class='detail-value'>" . htmlspecialchars(substr($record['address'], 0, 50)) . (strlen($record['address']) > 50 ? '...' : '') . "</div>";
            echo "</div>";
            
            echo "<div class='detail-row'>";
            echo "<div class='detail-label'>Country:</div>";
            echo "<div class='detail-value'>" . htmlspecialchars($record['country']) . "</div>";
            echo "</div>";
            
            echo "<div class='detail-row'>";
            echo "<div class='detail-label'>Registered:</div>";
            echo "<div class='detail-value'>" . htmlspecialchars($record['timestamp']) . "</div>";
            echo "</div>";
            
            echo "</div>";
            
            // Confirmation form
            echo "<div class='nav-links'>";
            echo "<form method='POST' style='display: inline;'>";
            echo "<input type='hidden' name='confirm' value='yes'>";
            echo "<button type='submit' class='btn delete-btn' onclick='return confirm(\"This will permanently delete the record. Are you absolutely sure?\")'>Yes, Delete Permanently</button>";
            echo "</form>";
            echo "<a href='list_data.php' class='cancel-btn'>Cancel</a>";
            echo "<a href='view_data.php?id=" . urlencode($record['id']) . "' class='back-btn'>View Full Record</a>";
            echo "</div>";
        }
        
        } catch(PDOException $e) {
            echo "<div class='error'>Database error: " . $e->getMessage() . "</div>";
            echo "<div class='nav-links'>";
            echo "<a href='list_data.php' class='back-btn'>← Back to All Registrations</a>";
            echo "</div>";
        }
        ?>
        
    </div>
</body>
</html>