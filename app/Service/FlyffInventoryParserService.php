<?php

namespace App\Service;

class FlyffInventoryParserService
{
    /**
     * Parse a Flyff inventory string into an array of items
     *
     * @param string $inventoryString The raw inventory string to parse
     * @return array An array of parsed inventory items
     */
    public function parse(string $inventoryString): array
    {
        // Remove trailing $ if present
        $inventoryString = rtrim($inventoryString, '$');
        
        // Split the inventory string by / to get individual item entries
        $itemStrings = explode('/', $inventoryString);
        
        $inventory = [];
        
        foreach ($itemStrings as $itemString) {
            // Skip empty entries
            if (empty($itemString)) {
                continue;
            }
            
            // Split the item string by comma to get individual values
            $itemData = explode(',', $itemString);
            
            // Parse durability which may be in format "current/max"
            $durabilityParts = explode('/', end($itemData));
            $currentDurability = $durabilityParts[0] ?? 0;
            $maxDurability = $durabilityParts[1] ?? 0;
            
            // Remove the durability from the array as we've extracted it separately
            array_pop($itemData);
            
            // Create item structure with proper field names
            $item = [
                'slot'              => (int) $itemData[0],
                'item_id'           => (int) $itemData[1],
                'quantity'          => (int) $itemData[3],
                'price'             => (int) $itemData[4],
                'flag'              => (int) $itemData[5],
                'serial_number'     => $itemData[6],
                'plus_value'        => (int) $itemData[7] ?? 0,
                'option'            => (int) $itemData[8] ?? 0,
                'element_type'      => (int) $itemData[9] ?? 0,
                'element_strength'  => (int) $itemData[10] ?? 0,
                'awakening'         => (int) $itemData[11] ?? 0,
                'stat_value1'       => (int) $itemData[12] ?? 0,
                'stat_value2'       => (int) $itemData[13] ?? 0,
                'stat_value3'       => (int) $itemData[14] ?? 0,
                'stat_value4'       => (int) $itemData[15] ?? 0,
                'current_durability' => (int) $currentDurability,
                'max_durability'    => (int) $maxDurability,
            ];
            
            $inventory[] = $item;
        }
        
        return $inventory;
    }
    
    /**
     * Serialize an array of items back to the Flyff inventory string format
     *
     * @param array $inventory Array of inventory items
     * @return string The serialized inventory string
     */
    public function serialize(array $inventory): string
    {
        $itemStrings = [];
        
        foreach ($inventory as $item) {
            $itemData = [
                $item['slot'],
                $item['item_id'],
                '', // Empty field
                $item['quantity'],
                $item['price'],
                $item['flag'],
                $item['serial_number'],
                $item['plus_value'] ?? 0,
                $item['option'] ?? 0,
                $item['element_type'] ?? 0,
                $item['element_strength'] ?? 0,
                $item['awakening'] ?? 0,
                $item['stat_value1'] ?? 0,
                $item['stat_value2'] ?? 0,
                $item['stat_value3'] ?? 0,
                $item['stat_value4'] ?? 0,
            ];
            
            // Add durability as last field
            if (isset($item['max_durability']) && $item['max_durability'] > 0) {
                $itemData[] = $item['current_durability'] . '/' . $item['max_durability'];
            } else {
                $itemData[] = '0';
            }
            
            $itemStrings[] = implode(',', $itemData);
        }
        
        return implode('/', $itemStrings) . '$';
    }

    /**
     * Find all items with a specific item ID
     *
     * @param array $inventory The parsed inventory array
     * @param int $itemId The item ID to search for
     * @return array Filtered inventory items matching the item ID
     */
    public function findItemsById(array $inventory, int $itemId): array
    {
        return array_filter($inventory, function($item) use ($itemId) {
            return $item['item_id'] === $itemId;
        });
    }

    /**
     * Find all items matching any of the provided item IDs
     *
     * @param array $inventory The parsed inventory array
     * @param array $itemIds Array of item IDs to search for
     * @return array Filtered inventory items matching any of the item IDs
     */
    public function findItemsByIds(array $inventory, array $itemIds): array
    {
        return array_filter($inventory, function($item) use ($itemIds) {
            return in_array($item['item_id'], $itemIds);
        });
    }

    /**
     * Parse inventory string and filter by specific item ID
     *
     * @param string $inventoryString The raw inventory string to parse
     * @param int $itemId The item ID to filter by
     * @return array Filtered inventory items
     */
    public function parseAndFilterById(string $inventoryString, int $itemId): array
    {
        $inventory = $this->parse($inventoryString);
        return $this->findItemsById($inventory, $itemId);
    }

    /**
     * Parse inventory string and filter by multiple item IDs
     *
     * @param string $inventoryString The raw inventory string to parse
     * @param array $itemIds The item IDs to filter by
     * @return array Filtered inventory items
     */
    public function parseAndFilterByIds(string $inventoryString, array $itemIds): array
    {
        $inventory = $this->parse($inventoryString);
        return $this->findItemsByIds($inventory, $itemIds);
    }
}