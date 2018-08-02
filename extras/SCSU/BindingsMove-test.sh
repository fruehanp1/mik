for file in *.cpd; do
    dir=${file%%.*}
    echo "$dir"
done
