<?php
/**
 * MongoDB Database Connection & Helper Functions
 * EduPortal Management System
 */

class MongoDBHelper {
    private static $manager = null;

    private static function getDbName() {
        return getenv('MONGODB_DB') ?: "student_portal";
    }

    private static function getUri() {
        return getenv('MONGODB_URI') ?: "mongodb://127.0.0.1:27017";
    }

    /**
     * Get or create a singleton MongoDB Manager instance
     */
    public static function getManager() {
        if (self::$manager === null) {
            try {
                self::$manager = new MongoDB\Driver\Manager(self::getUri());
            } catch (Exception $e) {
                die("MongoDB Connection Error: " . htmlspecialchars($e->getMessage()));
            }
        }
        return self::$manager;
    }

    /**
     * Find documents in a collection
     */
    public static function find($collection, $filter = [], $options = []) {
        $manager = self::getManager();
        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = $manager->executeQuery(self::getDbName() . "." . $collection, $query);
        return $cursor->toArray();
    }

    /**
     * Find a single document
     */
    public static function findOne($collection, $filter = []) {
        $results = self::find($collection, $filter, ['limit' => 1]);
        return count($results) > 0 ? (array)$results[0] : null;
    }

    /**
     * Insert a document
     */
    public static function insert($collection, $document) {
        $manager = self::getManager();
        $bulk = new MongoDB\Driver\BulkWrite();
        $id = $bulk->insert($document);
        $result = $manager->executeBulkWrite(self::getDbName() . "." . $collection, $bulk);
        return $result->getInsertedCount() > 0 ? $id : false;
    }

    /**
     * Update document(s)
     * $updateOps should be either ['$set' => [...]] or raw data which will be wrapped in ['$set' => $updateOps]
     */
    public static function update($collection, $filter, $updateOps, $multi = false, $upsert = false) {
        $manager = self::getManager();
        $bulk = new MongoDB\Driver\BulkWrite();

        // If top-level keys don't start with '$', wrap in '$set'
        $hasOperator = false;
        foreach (array_keys($updateOps) as $k) {
            if (str_starts_with($k, '$')) {
                $hasOperator = true;
                break;
            }
        }
        $update = $hasOperator ? $updateOps : ['$set' => $updateOps];

        $bulk->update($filter, $update, ['multi' => $multi, 'upsert' => $upsert]);
        $result = $manager->executeBulkWrite(self::getDbName() . "." . $collection, $bulk);
        return $result->getModifiedCount();
    }

    /**
     * Delete document(s)
     */
    public static function delete($collection, $filter, $limit = 1) {
        $manager = self::getManager();
        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->delete($filter, ['limit' => $limit]);
        $result = $manager->executeBulkWrite(self::getDbName() . "." . $collection, $bulk);
        return $result->getDeletedCount();
    }
}
