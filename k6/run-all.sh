#!/bin/bash

# Define the combinations
TYPES=("fpm" "octane")
RESOURCES=("hello" "users")

echo "🧪 Starting Full Performance Benchmark Suite..."
echo "------------------------------------------------"

for type in "${TYPES[@]}"; do
    for resource in "${RESOURCES[@]}"; do
        echo ""
        echo "▶️  EXECUTING: Engine=$type | Resource=$resource"
        echo "------------------------------------------------"
        
        # Run k6 with the specific environment variables
        k6 run -e TYPE=$type -e RESOURCE=$resource ./k6/k6.js
        
        echo "✅ Finished: $resource-$type"
        echo "⏳ Cooling down for 5 seconds..."
        sleep 5
    done
done

echo ""
echo "✨ All benchmark scenarios have been completed!"
echo "📊 You can now view the results on your Performance Dashboard."
