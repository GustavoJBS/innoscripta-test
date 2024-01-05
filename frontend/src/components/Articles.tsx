
import { ArticleInterface } from "@/app/(admin-routes)/home/page";
import ArticleCard from "./ArticleCard";

type ArticlesProps = {
    articles: ArticleInterface[]
};

const Articles: React.FC<ArticlesProps> = ({ articles }) => {
    return (
        <div className="flex flex-col">
            <div className="w-full grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                {
                    articles.map((article: ArticleInterface) => (
                        <ArticleCard
                            key={article.id}
                            article={article}
                        />
                    ))
                }
            </div>

            {
                articles.length === 0 && (
                    <div className="text-xl font-semibold w-full text-center">
                        No Articles Found
                    </div>
                )
            }
        </div>
    )
}

export default Articles; 