import sys
import json
import joblib
import numpy as np


# Load the pre-trained diabetes model
model_path = "diabetes_model.sav"
model = joblib.load(model_path)

def predict_diabetes(inputs):
    # Reshape inputs to match model's expected input shape
    input_array = np.array(inputs).reshape(1, -1)
    # Get prediction (0 for low risk of diabetes, 1 for high risk of diabetes)
    
    prediction = model.predict(input_array)
    # Return the result
    return int(prediction[0])

if __name__ == "__main__":
    # Expect 8 inputs in the order specified in the PHP file
    if len(sys.argv) != 9:
        print(json.dumps({"error": "Invalid input. Expected 9 input values."}))
        sys.exit(1)
    
    try:
        # Parse inputs as floats
        inputs = [float(arg) for arg in sys.argv[1:]]
        # Get prediction result
        result = predict_diabetes(inputs)
        # Output the result as JSON
        print(json.dumps({"result": result}))
    
    except ValueError:
        print(json.dumps({"error": "Invalid input. All values must be numeric."}))
        sys.exit(1)
