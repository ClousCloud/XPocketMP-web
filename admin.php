<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <h2>Contact Messages</h2>

    <?php if (!empty($contacts)): ?>
        <ul>
            <?php foreach ($contacts as $contact): ?>
                <li>
                    <strong><?php echo htmlspecialchars($contact->name); ?></strong> (<?php echo htmlspecialchars($contact->email); ?>):<br>
                    <?php echo nl2br(htmlspecialchars($contact->message)); ?>
                    <form method="post" action="/admin/deleteContact">
                        <input type="hidden" name="id" value="<?php echo $contact->id; ?>">
                        <button type="submit">Delete</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No contact messages found.</p>
    <?php endif; ?>
</body>
</html>