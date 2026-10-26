<?php
    // Enable full error reporting for debugging
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once("database.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $noteID = filter_input(INPUT_POST, 'note_id', FILTER_VALIDATE_INT);
        $title = filter_input(INPUT_POST, 'title');
        $category = filter_input(INPUT_POST, 'category');
        $content = filter_input(INPUT_POST, 'content');

        // DEBUG: Uncomment the next line to see if your form data is arriving
        // echo "ID: $noteID | Title: $title | Category: $category | Content: $content"; exit();

        if ($noteID && $title && $content) {
            try {
                $query = 'UPDATE notes 
                          SET title = :title, 
                              category = :category, 
                              content = :content 
                          WHERE noteID = :noteID';
                
                $stmt = $db->prepare($query);
                $stmt->bindValue(':title', $title);
                $stmt->bindValue(':category', $category ?? 'General');
                $stmt->bindValue(':content', $content);
                $stmt->bindValue(':noteID', $noteID);
                $stmt->execute();
                $stmt->closeCursor();

                header('Location: notes.php');
                exit();
            } catch (PDOException $e) {
                // Show exact database error if the query fails
                echo "Database Error: " . $e->getMessage();
                exit();
            }
        } else {
            echo "Error: Missing required fields (Note ID, Title, or Content).";
            exit();
        }
    } else {
        header('Location: notes.php');
        exit();
    }
?>